<?php

use Auth\Authorizer;
use Auth\ConversationAuthorizer;
use Routing\Route;

use Response\FlashData;
use Response\HTTPRenderer;
use Response\Render\HTMLRenderer;
use Response\Render\JSONRenderer;
use Response\Render\RedirectRenderer;

use Helpers\Authenticate;
use Helpers\ValidationHelper;

use Database\DataAccess\DAOFactory;

use Models\Conversation;
use Models\DirectMessge;
use Models\File;
use Models\Like;
use Models\Post;
use Models\Profile;
use Models\User;
use Services\Conversation\ConversationParnerResolver;
use Services\Image\ImagePathGenerator;
use Services\Image\ImagePathResolver;
use Services\Image\ImageStorage;
use Services\Image\ImageUrlBuilder;
use Types\ValueType;

return [
    'login' => Route::create('login', function (): HTTPRenderer {
        return new HTMLRenderer('page/login');
    })->setMiddleware(['guest']),
    'register' => Route::create('register', function (): HTTPRenderer {
        return new HTMLRenderer('page/register');
    })->setMiddleware(['guest']),
    'logout' => Route::create('logout', function (): HTTPRenderer {
        Authenticate::logoutUser();
        FlashData::setFlashData('success', 'Logged out.');
        return new RedirectRenderer('login');
    })->setMiddleware(['auth']),

    '' => Route::create('', function(): HTTPRenderer {
        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'GET') throw new Exception('Invalid request method!');

            $authUser = Authenticate::getAuthenticatedUser();
            if($authUser === null) return new RedirectRenderer('login');
    
            $profileDAO = DAOFactory::getProfileDAO();
            $authUserProfile = $profileDAO->getByUserId($authUser->getId());
            if($authUserProfile === null) {
                return new RedirectRenderer('login');
            }
            
            $imageUrlBuilder = new ImageUrlBuilder(); 
            $imagePathResolver = new ImagePathResolver($imageUrlBuilder);

            $imagePathResolver->resolveProfile($authUserProfile);

            // TODO: フォロワータブとおすすめタブで取得するPostを変えるロジックにする
            $postDAO = DAOFactory::getPostDAO();
            $posts = $postDAO->getFollowingPosts($authUserProfile->getUserId());
            
            if($posts !== null) {
                foreach($posts as $postData) {
                    $publicPostImagePath = $imageUrlBuilder->buildPostImageUrl($postData['post']->getImagePath());
                    $publicAuthorImagePath = $imageUrlBuilder->buildProfileImageUrl($postData['author']->getImagePath());
                    
                    $postData['post']->setImagePath($publicPostImagePath);
                    $postData['author']->setImagePath($publicAuthorImagePath);
                }
            }

            return new HTMLRenderer('page/home', [
                'authUser' => $authUserProfile,
                'posts' => $posts,
                'postsCount' => $posts === null ? 0 : count($posts)
            ]);
        } catch (\Exception $e) {
            error_log($e->getMessage());

            return new RedirectRenderer('login');
        }
    })->setMiddleware(['auth']),
    'profile' => Route::create('profile', function(): HTTPRenderer {
        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'GET') throw new Exception('Invalid request method!');

            $authUser = Authenticate::getAuthenticatedUser();
            if($authUser === null) return new RedirectRenderer('login');
            
            $requiredFields = [
                'user' => ValueType::STRING
            ];
            $validatedData = ValidationHelper::validateFields($requiredFields, $_GET);

            $profileDAO = DAOFactory::getProfileDAO();
            $queryUserProfile = $profileDAO->getByUsername($validatedData['user']);
            if($queryUserProfile === null) return new RedirectRenderer('login');
            
            $authUserProfile = $profileDAO->getByUserId($authUser->getId());
            if($authUserProfile === null) return new RedirectRenderer('login');

            $imageUrlBuilder = new ImageUrlBuilder();
            $imagePathResolver = new ImagePathResolver($imageUrlBuilder);
            
            $imagePathResolver->resolveProfile($queryUserProfile);
            $imagePathResolver->resolveProfile($authUserProfile);

            $postDAO = DAOFactory::getPostDAO();
            $posts = $postDAO->getByUserId($queryUserProfile->getUserId());
            if($posts !== null) {
                foreach ($posts as &$data) {
                    $data['author'] = $queryUserProfile;
                }
                if(isset($data)) {
                    unset($data);
                }

                foreach($posts as $data) {
                    $publicPostImagePath = $imageUrlBuilder->buildPostImageUrl($data['post']->getImagePath());
                    $data['post']->setImagePath($publicPostImagePath);
                }
            };

            $followDAO = DAOFactory::getFollowDAO();
            $followerCount = $followDAO->getFollowerCount($queryUserProfile->getUserId());
            $followingCount = $followDAO->getFollowingCount($queryUserProfile->getUserId());
            $isFollow = $followDAO->checkIsFollow($authUserProfile->getUserId(), $queryUserProfile->getUserId());
            
            return new HTMLRenderer('page/profile', [
                'isFollow' => $isFollow,
                'authUser' => $authUserProfile,
                'queryUser' => $queryUserProfile,
                'posts' => $posts,
                'followerCount' => $followerCount,
                'followingCount' => $followingCount,
            ]);
        } catch (\InvalidArgumentException $e) {
            error_log($e->getMessage());

            return new RedirectRenderer('');
        } catch (\Exception $e) {
            error_log($e->getMessage());

            return new JSONRenderer(['status' => 'error']);
        }

    })->setMiddleware(['auth']),
    'post' => Route::create('post', function(): HTTPRenderer {
        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'GET') throw new Exception('Invalid request method!');

            $authUser = Authenticate::getAuthenticatedUser();
            if($authUser === null) return new RedirectRenderer('login');
            
            $profileDAO = DAOFactory::getProfileDAO();
            $authUserProfile = $profileDAO->getByUserId($authUser->getId());
            if($authUserProfile === null) return new RedirectRenderer('login');
            
            $imageUrlBuilder = new ImageUrlBuilder();
            $imagePathResolver = new ImagePathResolver($imageUrlBuilder);

            $imagePathResolver->resolveProfile($authUserProfile);

            $requiredFields = [
                'id' => ValueType::INT
            ];
            $validatedData = ValidationHelper::validateFields($requiredFields, $_GET);
            
            $postDAO = DAOFactory::getPostDAO();
            $post = $postDAO->getById($validatedData['id'], $authUserProfile->getUserId());
            if($post === null) return new RedirectRenderer('');
            
            $imagePathResolver->resolvePost($post['post']);
            $imagePathResolver->resolveProfile($post['author']);
            
            $replies = $postDAO->getReplies($validatedData['id'], $authUserProfile->getUserId());
            if($replies != null) {
                foreach($replies as $data) {
                    $publicReplyImagePath = $imageUrlBuilder->buildPostImageUrl($data['post']->getImagePath());
                    $publicAuthorUserImagePath = $imageUrlBuilder->buildProfileImageUrl($data['author']->getImagePath());

                    $data['post']->setImagePath($publicReplyImagePath);
                    $data['author']->setImagePath($publicAuthorUserImagePath);
                }
            }
            
            return new HTMLRenderer('page/post', [
                'authUser' => $authUserProfile,
                'data' => $post,
                'replies' => $replies,
            ]);
        } catch (\InvalidArgumentException $e) {
            error_log($e->getMessage());

            return new RedirectRenderer('');
        } catch (\Exception $e) {
            error_log($e->getMessage());

            return new RedirectRenderer('login');
        }

    })->setMiddleware(['auth']),
    'following' => Route::create('following', function(): HTTPRenderer {
        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'GET') throw new Exception('Invalid request method!');

            $authUser = Authenticate::getAuthenticatedUser();
            if($authUser === null) return new RedirectRenderer('login');
    
            $profileDAO = DAOFactory::getProfileDAO();
            $authUserProfile = $profileDAO->getByUserId($authUser->getId());
            if($authUserProfile === null) return new RedirectRenderer('login');

            $imageUrlBuilder = new ImageUrlBuilder();
            $imagePathResolver = new ImagePathResolver($imageUrlBuilder);
            
            $imagePathResolver->resolveProfile($authUserProfile);

            $followDAO = DAOFactory::getFollowDAO();
            $following = $followDAO->getFollowing($authUserProfile->getUserId());
            
            if($following !== null) {
                foreach($following as $user) {
                    $publicAuthorImagePath = $imageUrlBuilder->buildProfileImageUrl($user->getImagePath());
                    $user->setImagePath($publicAuthorImagePath);
                };
            }
            
            return new HTMLRenderer('page/following', [
                'authUser' => $authUserProfile,
                'data' => $following,
            ]);
        } catch (\Exception $e) {
            error_log($e->getMessage());
            return new RedirectRenderer('login');
        }
    })->setMiddleware(['auth']),
    'follower' => Route::create('follower', function(): HTTPRenderer {
        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'GET') throw new Exception('Invalid request method!');

            $authUser = Authenticate::getAuthenticatedUser();
            if($authUser === null) return new RedirectRenderer('login');
    
            $profileDAO = DAOFactory::getProfileDAO();
            $authUserProfile = $profileDAO->getByUserId($authUser->getId());
            if($authUserProfile === null) return new RedirectRenderer('login');

            $imageUrlBuilder = new ImageUrlBuilder();
            $imagePathResolver = new ImagePathResolver($imageUrlBuilder);
            $imagePathResolver->resolveProfile($authUserProfile);

            $followDAO = DAOFactory::getFollowDAO();
            $followers = $followDAO->getFollower($authUserProfile->getUserId());
            
            if($followers !== null) {
                foreach($followers as $user) {
                    $publicAuthorImagePath = $imageUrlBuilder->buildProfileImageUrl($user->getImagePath());
                    $user->setImagePath($publicAuthorImagePath);
                };
            }

            return new HTMLRenderer('page/follower', [
                'authUser' => $authUserProfile,
                'data' => $followers,
            ]);
        } catch (\Exception $e) {
            error_log($e->getMessage());
            return new RedirectRenderer('login');
        }
    })->setMiddleware(['auth']),
    'messages' => Route::create('message', function(): HTTPRenderer {
        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'GET') throw new Exception('Invalid request method!');

            $authUser = Authenticate::getAuthenticatedUser();
            if($authUser === null) return new RedirectRenderer('login');

            $profileDAO = DAOFactory::getProfileDAO();
            $authUserProfile = $profileDAO->getByUserId($authUser->getId());
            if($authUserProfile === null) return new RedirectRenderer('login');

            $imageUrlBuilder = new ImageUrlBuilder();
            $imagePathResolver = new ImagePathResolver($imageUrlBuilder);
            
            $imagePathResolver->resolveProfile($authUserProfile);
            
            $conversationDAO = DAOFactory::getConversationDAO();
            $conversations = $conversationDAO->findAllByUserId($authUser->getId());
            
            if(!empty($conversations)) {
                foreach($conversations as $data) {
                    $imagePathResolver->resolveProfile($data['partner']);
                }
            }

            $followDAO = DAOFactory::getFollowDAO();
            $followers = $followDAO->getFollower($authUserProfile->getUserId());
            
            if($followers !== null) {
                foreach($followers as $user) {
                    $publicAuthorImagePath = $imageUrlBuilder->buildProfileImageUrl($user->getImagePath());
                    $user->setImagePath($publicAuthorImagePath);
                };
            }

            return new HTMLRenderer('page/messages', [
                'authUser' => $authUserProfile,
                'conversations' => $conversations,
                'followers' => $followers
            ]);
        } catch (\Exception $e) {
            error_log($e->getMessage());

            return new RedirectRenderer('login');
        }
    }),
    'message' => Route::create('message', function(): HTTPRenderer {
        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'GET') throw new Exception('Invalid request method!');
            
            $authUser = Authenticate::getAuthenticatedUser();
            if($authUser === null) return new RedirectRenderer('login');

            $profileDAO = DAOFactory::getProfileDAO();
            $authUserProfile = $profileDAO->getByUserId($authUser->getId());
            if($authUserProfile === null)  return new RedirectRenderer('login');

            $requiredFields = [
                'id' => ValueType::INT
            ];
            $validatedData = ValidationHelper::validateFields($requiredFields, $_GET);

            $conversationDAO = DAOFactory::getConversationDAO();            
            $conversation = $conversationDAO->findByConversationId($validatedData['id']);
            if($conversation === null)  return new RedirectRenderer('messages');
            
            $authUserId = $authUserProfile->getUserId();
            $conversationParnerResolver = new ConversationParnerResolver($profileDAO);
            $partnerProfile = $conversationParnerResolver->resolverPartnerProfile($authUserId, $conversation);
            if($partnerProfile === null) return new RedirectRenderer('login');
            
            $imageUrlBuilder = new ImageUrlBuilder();

            $imagePathResolver = new ImagePathResolver($imageUrlBuilder);
            $imagePathResolver->resolveProfile($authUserProfile);
            $imagePathResolver->resolveProfile($partnerProfile);

            $conversations = $conversationDAO->findAllByUserId($authUser->getId());
            if(!empty($conversations)) {
                foreach($conversations as $data) {
                    $imagePathResolver->resolveProfile($data['partner']);
                }
            }

            $directMessageDAO = DAOFactory::getDirectMessage();
            $directMessages = $directMessageDAO->findAllByConversationId($conversation->getId());

            $followDAO = DAOFactory::getFollowDAO();
            $followers = $followDAO->getFollower($authUserProfile->getUserId());
            if($followers !== null) {
                foreach($followers as $user) {
                    $publicAuthorImagePath = $imageUrlBuilder->buildProfileImageUrl($user->getImagePath());

                    $user->setImagePath($publicAuthorImagePath);
                };
            }
            
            return new HTMLRenderer('page/message', [
                'conversation' => $conversation,
                'partner' => $partnerProfile,
                'directMessages' => $directMessages,
                'authUser' => $authUserProfile,
                'conversations' => $conversations,
                'followers' => $followers
            ]);           
        } catch (\InvalidArgumentException $e) {
            error_log($e->getMessage());

            return new RedirectRenderer('messages');
        } catch (\Exception $e) {
            error_log($e->getMessage());

            return new RedirectRenderer('login');
        }
    }),

    // Auth
    'form/login' => Route::create('form/login', function (): HTTPRenderer {
        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') throw new Exception('Invalid request method!');

            $required_fields = [
                'email' => ValueType::EMAIL,
                'password' => ValueType::STRING,
            ];

            $validatedData = ValidationHelper::validateAuth($required_fields, $_POST);

            Authenticate::authenticate($validatedData['email'], $validatedData['password']);

            FlashData::setFlashData('success', 'Logged in successfully.');
            return new RedirectRenderer('');
        } catch (AuthenticationFailureException $e) {
            error_log($e->getMessage());

            FlashData::setFlashData('error', 'Failed to login, wrong email and/or password.');
            return new RedirectRenderer('login');
        } catch (\InvalidArgumentException $e) {
            error_log($e->getMessage());

            FlashData::setFlashData('error', 'Invalid Data.');
            return new RedirectRenderer('login');
        } catch (Exception $e) {
            error_log($e->getMessage());

            FlashData::setFlashData('error', 'An error occurred.');
            return new RedirectRenderer('login');
        }
    })->setMiddleware(['guest']),
    'form/register' => Route::create('form/register', function (): HTTPRenderer {
        try {
            // リクエストメソッドがPOSTかどうかをチェックします
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') throw new Exception('Invalid request method!');

            $required_fields = [
                'username' => ValueType::STRING,
                'email' => ValueType::EMAIL,
                'password' => ValueType::PASSWORD,
                'confirm_password' => ValueType::PASSWORD,
            ];

            $userDao = DAOFactory::getUserDAO();

            // シンプルな検証
            $validatedData = ValidationHelper::validateAuth($required_fields, $_POST);

            if($validatedData['confirm_password'] !== $validatedData['password']){
                FlashData::setFlashData('error', 'Invalid Password!');
                return new RedirectRenderer('register');
            }

            // Eメールは一意でなければならないので、Eメールがすでに使用されていないか確認します
            if($userDao->getByEmail($validatedData['email'])){
                // FlashData::setFlashData('error', 'Email is already in use!');
                return new RedirectRenderer('register');
            }

            // 新しいUserオブジェクトを作成します
            $user = new User(
                email: $validatedData['email'],
            );

            // データベースにユーザーを作成しようとします
            $success = $userDao->create($user, $validatedData['password']);
            
            if (!$success) throw new Exception('Failed to create new user!');

            // User作成成功後、Profileを作成する
            $profileDAO = DAOFactory::getProfileDAO();
            $profile = new Profile(
                username: $validatedData['username'],
                userId: $user->getId()
            );

            $success = $profileDAO->create($profile);
            // もし失敗した場合、User情報のみがDB上に残るのでロールバック機能を付けることが必要
            if(!$success) throw new Exception('Failed to create new profile');
            
            // 検証メール処理
            // $userからid, emailを取得
            // $id = $user->getId();
            // $hashedEmail = hash('sha256', $user->getEmail());
            
            // 署名付き検証URLの生成(http://localhost:8000/email/verify?id={id}&user={user}?expiration={expiration}?signature={signature})
            // $queryParameters = [
            //     'id' => $id,
            //     'user' => $hashedEmail,
            //     'expiration' => time() + 1800,
            // ];
            // $url = Route::create('verify/email', function(){})->getSignedURL($queryParameters);
            // error_log(var_export($url, true));

            // Mail::sendVerifyEmail($url, $user->getEmail());

            Authenticate::loginAsUser($user);

            FlashData::setFlashData('success', 'Account successfully created.');
            return new RedirectRenderer('');
        } catch (\InvalidArgumentException $e) {
            error_log($e->getMessage());

            FlashData::setFlashData('error', 'Invalid Data.');
            return new RedirectRenderer('register');
        } catch (Exception $e) {
            error_log($e->getMessage());

            FlashData::setFlashData('error', 'An error occurred.');
            return new RedirectRenderer('register');
        }
    })->setMiddleware(['guest']),

    // Create
    'form/post' => Route::create('form/post', function(): HTTPRenderer {
        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') throw new Exception('Invalid request method!');

            $authUser = Authenticate::getAuthenticatedUser();
            if($authUser === null) return new RedirectRenderer('login');
            
            $requiredFields = [
                'content' => ValueType::STRING
            ];            
            $validatedData = ValidationHelper::validateFields($requiredFields, $_POST);

            $file = File::fromArray($_FILES['upload-file']);          
            if($file->isValid()) {
                $validatedFileData = ValidationHelper::validateFile($file);
                $publicPostImagePath = (new ImagePathGenerator())->generate($validatedFileData->getTypeWithoutPrefix());
            }

            $request = [
                'content' => $validatedData['content'],
                'imagePath' => $publicPostImagePath,
                'userId' => $authUser->getId(),
                'parentPostId' => null,
            ];
            
            $post = new Post(...$request);
            $postDAO = DAOFactory::getPostDAO();
            $success = $postDAO->create($post);
            if($success === false) throw new Exception('Failed Create Post');

            if($file->isValid()) {
                $imageStorage = new ImageStorage();
                $isSavedToDir = $imageStorage->save($publicPostImagePath, $validatedFileData->getTmpName());
                if($isSavedToDir === false) throw new Exception('Failed to save to directory.');
            }

            return new RedirectRenderer('');
        } catch (\InvalidArgumentException $e) {
            error_log($e->getMessage());

            return new RedirectRenderer('');
        } catch (\Exception $e) {
            error_log($e->getMessage());

            return new RedirectRenderer('login');
        }
    })->setMiddleware(['auth']),
    'form/follow' => Route::create('form/follow', function(): HTTPRenderer {
        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') throw new Exception('Invalid request method!');

            $authUser = Authenticate::getAuthenticatedUser();
            if($authUser === null) return new RedirectRenderer('login');
            $userId = $authUser->getId();

            $requiredFields = [
                'following_id' => ValueType::INT
            ];

            $validatedData = ValidationHelper::validateFields($requiredFields, $_POST);

            $followDAO = DAOFactory::getFollowDAO();
            $isFollow = $followDAO->checkIsFollow($userId, $validatedData['following_id']);
            $success = $isFollow ? 
                        $followDAO->unfollow($userId, $validatedData['following_id']) 
                        :
                        $followDAO->follow($userId, $validatedData['following_id']);

            if(!$success) throw new Exception('Failed follow');

            return new JSONRenderer(['status' => 'success']);
        } catch (\InvalidArgumentException $e) {
            error_log($e->getMessage());

            return new JSONRenderer(['status' => 'error']);
        } catch (\Exception $e) {
            error_log($e->getMessage());

            return new JSONRenderer(['status' => 'error']);
        }    
    })->setMiddleware(['auth']),
    'form/reply' => Route::create('form/reply', function(): HTTPRenderer {
        try {
            if($_SERVER['REQUEST_METHOD'] !== 'POST') throw new Exception('Invalid request method!');

            $authUser = Authenticate::getAuthenticatedUser();
            if($authUser === null) return new RedirectRenderer('login');

            $requiredFields = [
                'content' => ValueType::STRING,
                'parent_post_id' => ValueType::INT
            ];
            $validatedData = ValidationHelper::validateFields($requiredFields, $_POST);
            
            $file = File::fromArray($_FILES['upload-file']);          
            if($file->isValid()) {
                $validatedFileData = ValidationHelper::validateFile($file);
                $publicPostImagePath = (new ImagePathGenerator())->generate($validatedFileData->getTypeWithoutPrefix());
            }

            $postDAO = DAOFactory::getPostDAO();
            $parentPost = $postDAO->findParentPost($validatedData['parent_post_id']);
            if($parentPost === null) throw new Exception('Parent post is not exits.');

            $post = new Post(
                content: $validatedData['content'],
                imagePath: $publicPostImagePath,
                userId: $authUser->getId(),
                parentPostId: $parentPost->getId()
            );

            $success = $postDAO->create($post);
            if($success === false) throw new Exception('Failed to create reply!');

            if($file->isValid()) {
                $imageStorage = new ImageStorage();
                $isSavedToDir = $imageStorage->save($publicPostImagePath, $validatedFileData->getTmpName());
                if($isSavedToDir === false) throw new Exception('Failed to save to directory.');
            }

            return new RedirectRenderer('post?id=' . $validatedData['parent_post_id']);
        } catch (\InvalidArgumentException $e) {
            error_log($e->getMessage());

            return new JSONRenderer(['status' => 'error']);
        } catch (Exception $e) {
            error_log($e->getMessage());

            return new RedirectRenderer('login');
        }
    })->setMiddleware(['auth']),
    'form/like' => Route::create('form/like', function(): HTTPRenderer {
        try {
            if($_SERVER['REQUEST_METHOD'] !== 'POST') throw new Exception('Invalid request method!');

            $authUser = Authenticate::getAuthenticatedUser();
            if($authUser === null) return new RedirectRenderer('login');

            $requiredFields = [
                'post_id' => ValueType::INT
            ];
            $validatedData = ValidationHelper::validateFields($requiredFields, $_POST);

            $likeDAO = DAOFactory::getLikeDAO();
            $like = new Like(
                userId: $authUser->getId(),
                postId: $validatedData['post_id']
            );

            $isLiked = $likeDAO->checkIsLiked($like);

            $success = $isLiked ? $likeDAO->unlike($like) : $likeDAO->createLike($like);
            if($success === false) throw new Exception('Failed to like post!');
            
            return new JSONRenderer(['status' => 'success']);
        } catch (\InvalidArgumentException $e) {
            error_log($e->getMessage());

            return new JSONRenderer(['status' => 'error']);
        } catch (Exception $e) {
            error_log($e->getMessage());

            return new RedirectRenderer('login');
        }
    })->setMiddleware(['auth']),
    'form/conversation' => Route::create('form/conversation', function(): HTTPRenderer {
        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') throw new Exception('Invalid request method!');

            $authUser = Authenticate::getAuthenticatedUser();
            if($authUser === null) return new RedirectRenderer('login');

            $requiredFields = [
                'user1_id' => ValueType::INT,
                'user2_id' => ValueType::INT
            ];
            $validatedData = ValidationHelper::validateFields($requiredFields, $_POST);

            $conversationAuthorizer = new ConversationAuthorizer();
            
            if($conversationAuthorizer->isSameId($validatedData['user1_id'], $authUser->getId()) === false) throw new Exception('Invalid user1_id — not matching authenticated user.');
            
            if ($conversationAuthorizer->isSameId($validatedData['user1_id'], $validatedData['user2_id'])) throw new Exception('Cannot start a conversation with yourself.');
            
            $profileDAO = DAOFactory::getProfileDAO();
            $partnerProfile = $profileDAO->getByUserId($validatedData['user2_id']);
            if($partnerProfile === null) throw new Exception('Partner is not exists.');

            $followDAO = DAOFactory::getFollowDAO();
            $isMutualFollow = $conversationAuthorizer->isMutualFollow($followDAO, $authUser->getId(), $partnerProfile->getUserId());

            if($isMutualFollow === false) throw new Exception('AuthUser and PartnerUser is not mutualFollow.');

            $conversation = new Conversation(
                user1Id: $authUser->getId(),
                user2Id: $partnerProfile->getUserId(),
            );
            $conversationDAO = DAOFactory::getConversationDAO();

            $isExistsConversation = $conversationDAO->existsByUserPair($conversation);
            if($isExistsConversation) throw new Exception('Conversation already exists.');

            $success = $conversationDAO->create($conversation);
            if($success === false) throw new Exception('Failed to create conversation.');

            return new JSONRenderer(['status' => 'success']);
        } catch (\InvalidArgumentException $e) {
            error_log($e->getMessage());

            return new JSONRenderer(['status' => 'error']);
        } catch (\Exception $e) {
            error_log($e->getMessage());

            return new RedirectRenderer('login');
        }
    })->setMiddleware(['auth']),
    'form/direct-message' => Route::create('form/message', function(): HTTPRenderer {
        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') throw new Exception('Invalid request method!');

            $authUser = Authenticate::getAuthenticatedUser();
            if($authUser === null) return new RedirectRenderer('login');

            $requiredFields = [
                'conversation_id' => ValueType::INT,
                'content' => ValueType::STRING
            ];
            $validatedData = ValidationHelper::validateFields($requiredFields, $_POST);

            $conversationDAO = DAOFactory::getConversationDAO();
            $conversation = $conversationDAO->findByConversationId($validatedData['conversation_id']);
            if($conversation === null) throw new Exception('Do not exists conversation. ID: ' . $validatedData['conversation_id']);

            $conversationAuthorizer = new ConversationAuthorizer();
            $isJoinTheConversation = $conversationAuthorizer->isJoin($authUser->getId(), $conversation);
            if($isJoinTheConversation === false) throw new Exception('Cannnot the action.');

            $profileDAO = DAOFactory::getProfileDAO();
            $partnerProfile = $conversationAuthorizer->isExistsPartnerUser($authUser->getId(), $conversation, $profileDAO);
            if($partnerProfile === false) throw new Exception('Partner User is not exists.');

            $directMessage = new DirectMessge(
                conversationId: $validatedData['conversation_id'],
                senderId: $authUser->getId(),
                content: $validatedData['content']
            );

            $directMessageDAO = DAOFactory::getDirectMessage();
            $success = $directMessageDAO->create($directMessage);
            if($success === false) throw new Exception('Failed to create direct message.');
            
            return new RedirectRenderer("message?id={$conversation->getId()}");
        } catch (\Exception $e) {
            error_log($e->getMessage());

            return new RedirectRenderer('login');
        }
    })->setMiddleware(['auth']),

    // Update
    'form/update/profile' => Route::create('form/update/profile', function(): HTTPRenderer {
        try {
            if($_SERVER['REQUEST_METHOD'] !== 'POST') throw new Exception('Invalid request method!');

            $authUser = Authenticate::getAuthenticatedUser();
            if($authUser === null) return new RedirectRenderer('login');

            $requiredFields = [
                'username' => ValueType::STRING,
                'age' => ValueType::INT,
                'address' => ValueType::STRING,
                'hobby' => ValueType::STRING,
                'self_introduction' => ValueType::STRING
            ];

            $validatedData = ValidationHelper::validateFields($requiredFields, $_POST);
            // TODO: それぞれのfieldの最大値、最小値のバリデーションを検証する(ビジネスロジック)
                        
            $profile = new Profile(
                username: $validatedData['username'],
                userId: $authUser->getId(),
                age: $validatedData['age'],
                address: $validatedData['address'],
                hobby: $validatedData['hobby'],
                selfIntroduction: $validatedData['self_introduction']
            );
            
            $profileDAO = DAOFactory::getProfileDAO();
            $success = $profileDAO->updateProfile($profile);
            if($success === false) throw new Exception('Failed to update profile!');

            return new RedirectRenderer('profile?user=' . $validatedData['username']);
        } catch (\InvalidArgumentException $e) {
            error_log($e->getMessage());

            return new JSONRenderer(['status' => 'error']);
        } catch (Exception $e) {
            error_log($e->getMessage());

            return new RedirectRenderer('login');
        }
    })->setMiddleware(['auth']),
    'form/update/profile/icon' => Route::create('form/update/profile/icon', function(): HTTPRenderer {
        try {
            if($_SERVER['REQUEST_METHOD'] !== 'POST') throw new Exception('Invalid request method!');

            $authUser = Authenticate::getAuthenticatedUser();
            if($authUser === null) return new RedirectRenderer('login');
            
            $file = File::fromArray($_FILES['upload-file']);
            if($file->isValid() === false) return new JSONRenderer(['status' => 'File upload is invalid.']);

            $validatedFileData = ValidationHelper::validateFile($file);

            $publicProfileIconPath = (new ImagePathGenerator())->generate($validatedFileData->getTypeWithoutPrefix());
            
            $profileDAO = DAOFactory::getProfileDAO();
            $profile = $profileDAO->getByUserId($authUser->getId());
            $prevProfileImagePath = $profile->getImagePath();
            if($profile === null) return new RedirectRenderer('login');

            $success = $profileDAO->updataPrpfileIcon($publicProfileIconPath, $authUser->getId());
            if($success === false) throw new Exception('Failed to update profile!');

            $imageStorage = new ImageStorage();
            $imageStorage->save($publicProfileIconPath, $validatedFileData->getTmpName());
            
            if($prevProfileImagePath !== null) {
                $imageStorage->delete($prevProfileImagePath);
            }
            
            return new RedirectRenderer('profile?user=' . $profile->getUsername());
        } catch (\InvalidArgumentException $e) {
            error_log($e->getMessage());

            return new JSONRenderer(['status' => 'Invalid error.']);
        } catch (Exception $e) {
            error_log($e->getMessage());

            return new JSONRenderer(['status' => 'An unexpected error has occurred.']);
        }
    })->setMiddleware(['auth']),

    // Delete
    'form/delete/post' => Route::create('form/delete/post', function(): HTTPRenderer {
        try {
            if($_SERVER['REQUEST_METHOD'] !== 'POST') throw new Exception('Invalid request method!');

            $authUser = Authenticate::getAuthenticatedUser();
            if($authUser === null) return new RedirectRenderer('login');

            $requiredFields = [
                'post_id' => ValueType::INT
            ];
            $validatedData = ValidationHelper::validateFields($requiredFields, $_POST);

            $postDAO = DAOFactory::getPostDAO();
            $post = $postDAO->getById($validatedData['post_id'], $authUser->getId());
            if($post === null) throw new Exception('Target post is not exits.');

            if(Authorizer::isOwnedByUser($post->getUserId(), $authUser->getId(),) === false) throw new Exception('Cannnot the action.');
            
            $success = $postDAO->deletePost($validatedData['post_id']);
            if($success === false) throw new Exception('Failed to delete post!');

            return new RedirectRenderer('');
        } catch (\InvalidArgumentException $e) {
            error_log($e->getMessage());

            return new JSONRenderer(['status' => 'error']);
        } catch (\Exception $e) {
            error_log($e->getMessage());

            return new RedirectRenderer('login');
        }
    })->setMiddleware(['auth']),
    'form/delete/conversation' => Route::create('form/delete/conversation', function(): HTTPRenderer {
        try {
            if($_SERVER['REQUEST_METHOD'] !== 'POST') throw new Exception('Invalid request method!');

            $authUser = Authenticate::getAuthenticatedUser();
            if($authUser === null) return new RedirectRenderer('login');

            $requiredFields = [
                'conversation_id' => ValueType::INT
            ];
            $validatedData = ValidationHelper::validateFields($requiredFields, $_POST);

            $conversationDAO = DAOFactory::getConversationDAO();
            $conversation = $conversationDAO->findByConversationId($validatedData['conversation_id']);
            if($conversation === null) throw new Exception('Do not exists conversation. ID: ' . $validatedData['conversation_id']);

            if(Authorizer::isOwnedByUser($conversation->getUser1Id(), $authUser->getId()) === false) throw new Exception('Cannnot the action.');

            $success = $conversationDAO->delete($conversation->getId());
            if($success === false) throw new Exception('Failed to delete conversation.');
            
            return new RedirectRenderer('messages');
        } catch (\InvalidArgumentException $e) {
            error_log($e->getMessage());

            return new JSONRenderer(['status' => 'error']);
        } catch (\Exception $e) {
            error_log($e->getMessage());

            return new RedirectRenderer('login');
        }
    })->setMiddleware(['auth']),
];

