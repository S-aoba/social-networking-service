<?php

use Database\DataAccess\DAOFactory;
use Faker\ValidGenerator;
use Helpers\Authenticate;
use Helpers\ValidationHelper;
use Models\Conversation;
use Models\DirectMessge;
use Models\ImageService;
use Models\Like;
use Models\Post;
use Models\Profile;
use Models\User;
use Response\FlashData;
use Response\HTTPRenderer;
use Response\Render\HTMLRenderer;
use Response\Render\JSONRenderer;
use Response\Render\RedirectRenderer;
use Routing\Route;
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
    
            $postDAO = DAOFactory::getPostDAO();
            // TODO: フォロワータブとおすすめタブで取得するPostを変えるロジックにする
            $posts = $postDAO->getFollowingPosts($authUserProfile->getUserId());

            $imageService = new ImageService();
            
            if($posts !== null) {
                foreach($posts as $data) {
                    $publicPostImagePath = $imageService->buildPublicPostImagePath($data['post']->getImagePath());
                    $publicAuthorImagePath = $imageService->buildPublicProfileImagePath($data['author']->getImagePath());
                    
                    $data['post']->setImagePath($publicPostImagePath);
                    $data['author']->setImagePath($publicAuthorImagePath);
                }
            }

            $publicAuthUserImagePath = $imageService->buildPublicProfileImagePath($authUserProfile->getImagePath());
            $authUserProfile->setImagePath($publicAuthUserImagePath);

            return new HTMLRenderer('page/home', [
                'authUser' => $authUserProfile,
                'posts' => $posts,
            ]);
        } catch (\Exception $e) {
            error_log($e->getMessage());

            return new RedirectRenderer('login');
        }
    })->setMiddleware(['auth']),
    'profile' => Route::create('profile', function(): HTTPRenderer {
        try {
            $authUser = Authenticate::getAuthenticatedUser();
            
            if($authUser === null) return new RedirectRenderer('login');
            
            $requiredFields = [
                'user' => ValueType::STRING
            ];
            $validatedData = ValidationHelper::validateFields($requiredFields, $_GET);

            $profileDAO = DAOFactory::getProfileDAO();
            $queryUserProfile = $profileDAO->getByUsername($validatedData['user']);
            if($queryUserProfile === null) {
                return new RedirectRenderer('login');
            }

            $authUserProfile = $profileDAO->getByUserId($authUser->getId());
            if($authUserProfile === null) {
                return new RedirectRenderer('login');
            }

            $imageService = new ImageService();
            $publicAuthUserImagePath = $imageService->buildPublicProfileImagePath($authUserProfile->getImagePath());
            $authUserProfile->setImagePath($publicAuthUserImagePath);

            $publicQueryUserImagePath = $imageService->buildPublicProfileImagePath($queryUserProfile->getImagePath());
            $queryUserProfile->setImagePath($publicQueryUserImagePath);
            
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
                    $publicPostImagePath = $imageService->buildPublicPostImagePath($data['post']->getImagePath());
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
            $authUser = Authenticate::getAuthenticatedUser();
            if($authUser === null) return new RedirectRenderer('login');
            
            $requiredFields = [
                'id' => ValueType::INT
            ];
            $validatedData = ValidationHelper::validateFields($requiredFields, $_GET);
    
            $profileDAO = DAOFactory::getProfileDAO();
            $authUserProfile = $profileDAO->getByUserId($authUser->getId());
            if($authUserProfile === null) {
                return new RedirectRenderer('login');
            }

            $postDAO = DAOFactory::getPostDAO();
            $post = $postDAO->getById($validatedData['id'], $authUserProfile->getUserId());
            if($post === null) throw new Exception('Post not found!');
            
            $imageService = new ImageService();
            $publicAuthUserImagePath = $imageService->buildPublicProfileImagePath($authUserProfile->getImagePath());
            $authUserProfile->setImagePath($publicAuthUserImagePath);

            $publicPostImagePath = $imageService->buildPublicPostImagePath($post['post']->getImagePath());
            $publicAuthUserImagePath = $imageService->buildPublicProfileImagePath($post['author']->getImagePath());
            $post['post']->setImagePath($publicPostImagePath);
            $post['author']->setImagePath($publicAuthUserImagePath);

            $replies = $postDAO->getReplies($validatedData['id'], $authUserProfile->getUserId());
            if($replies != null) {
                foreach($replies as $data) {
                    $publicReplyImagePath = $imageService->buildPublicPostImagePath($data['post']->getImagePath());
                    $publicAuthUserImagePath = $imageService->buildPublicProfileImagePath($data['author']->getImagePath());
                    $data['post']->setImagePath($publicReplyImagePath);
                    $data['author']->setImagePath($publicAuthUserImagePath);
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
            $authUser = Authenticate::getAuthenticatedUser();
    
            if($authUser === null) return new RedirectRenderer('login');
    
            $profileDAO = DAOFactory::getProfileDAO();
            $authUserProfile = $profileDAO->getByUserId($authUser->getId());
            if($authUserProfile === null) {
                return new RedirectRenderer('login');
            }

            $imageService = new ImageService();
            
            $publicAuthUserImagePath = $imageService->buildPublicProfileImagePath($authUserProfile->getImagePath());
            $authUserProfile->setImagePath($publicAuthUserImagePath);

            $followDAO = DAOFactory::getFollowDAO();
            $following = $followDAO->getFollowing($authUserProfile->getUserId());
            if($following === null) throw new Exception('Following not found!');

            foreach($following as $user) {
                $publicAuthorImagePath = $imageService->buildPublicProfileImagePath($user->getImagePath());
                $user->setImagePath($publicAuthorImagePath);
            };
            
            return new HTMLRenderer('page/follower', [
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
            $authUser = Authenticate::getAuthenticatedUser();
    
            if($authUser === null) return new RedirectRenderer('login');
    
            $profileDAO = DAOFactory::getProfileDAO();
            $authUserProfile = $profileDAO->getByUserId($authUser->getId());
            if($authUserProfile === null) {
                return new RedirectRenderer('login');
            }
            $imageService = new ImageService();
            
            $publicAuthUserImagePath = $imageService->buildPublicProfileImagePath($authUserProfile->getImagePath());
            $authUserProfile->setImagePath($publicAuthUserImagePath);

            $followDAO = DAOFactory::getFollowDAO();
            $follower = $followDAO->getFollower($authUserProfile->getUserId());
            if($follower === null) throw new Exception('Follower not found!');
            
            foreach($follower as $user) {
                $publicAuthorImagePath = $imageService->buildPublicProfileImagePath($user->getImagePath());
                $user->setImagePath($publicAuthorImagePath);
            };

            return new HTMLRenderer('page/follower', [
                'authUser' => $authUserProfile,
                'data' => $follower,
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
            if($authUserProfile === null) {
                return new RedirectRenderer('login');
            }

            $imageService = new ImageService();
            $publicAuthUserImagePath = $imageService->buildPublicProfileImagePath($authUserProfile->getImagePath());
            $authUserProfile->setImagePath($publicAuthUserImagePath);

            $conversationDAO = DAOFactory::getConversationDAO();
            $conversations = $conversationDAO->findAllByUserId($authUser->getId());

            return new HTMLRenderer('page/messages', [
                'authUser' => $authUserProfile,
                'conversations' => $conversations
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
            if($authUserProfile === null) {
                return new RedirectRenderer('login');
            }
            
            $imageService = new ImageService();
            $publicAuthUserImagePath = $imageService->buildPublicProfileImagePath($authUserProfile->getImagePath());
            $authUserProfile->setImagePath($publicAuthUserImagePath);

            // TODO: coversations idを使用して個別のDMのデータを取得する

            return new HTMLRenderer('page/message', [
                'authUser' => $authUserProfile
            ]);
        } catch (\Exception $e) {
            error_log($e->getMessage());

            return new RedirectRenderer('login');
        }
    }),

    'form/login' => Route::create('form/login', function (): HTTPRenderer {
        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') throw new Exception('Invalid request method!');

            $required_fields = [
                'email' => ValueType::EMAIL,
                'password' => ValueType::STRING,
            ];

            $validatedData = ValidationHelper::validateFields($required_fields, $_POST);

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
            $validatedData = ValidationHelper::validateFields($required_fields, $_POST);

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
    'form/post' => Route::create('form/post', function(): HTTPRenderer {
        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') throw new Exception('Invalid request method!');

            $user = Authenticate::getAuthenticatedUser();
            if($user === null) return new RedirectRenderer('login');
            $userId = $user->getId();
            
            $requiredFields = [
                'content' => ValueType::STRING
            ];            
            $validatedData = ValidationHelper::validateFields($requiredFields, $_POST);

            $file = $_FILES['upload-file'];
            $imageService = null;
            $publicPostImagePath = null;
            if(isset($file) && $file['error'] === UPLOAD_ERR_OK) {
                $validatedFileData = ValidationHelper::validateFile($file);
                $imageService = new ImageService(
                    type: $validatedFileData['type'],
                    tempPath: $file['tmp_name'],
                );
                $publicPostImagePath = $imageService->generatePublicImagePath();
            }

            $request = [
                'content' => $validatedData['content'],
                'imagePath' => $publicPostImagePath,
                'userId' => $userId,
                'parentPostId' => null,
            ];
            
            $post = new Post(...$request);
            $postDAO = DAOFactory::getPostDAO();
            $success = $postDAO->create($post);
            if($success === false) throw new Exception('Failed Create Post');

            if(isset($file) && $file['error'] === UPLOAD_ERR_OK) {
                $isSavedToDir = $imageService->saveToDir($publicPostImagePath);
                if($isSavedToDir === false) throw new Exception('Failed to save to directory.');
            }

            return new RedirectRenderer('');
        } catch (\InvalidArgumentException $e) {
            error_log($e->getMessage());

            return new JSONRenderer(['status' => 'error']);
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
    'form/delete/post' => Route::create('form/delete/post', function(): HTTPRenderer {
        try {
            if($_SERVER['REQUEST_METHOD'] !== 'POST') throw new Exception('Invalid request method!');

            $authUser = Authenticate::getAuthenticatedUser();
            if($authUser === null) return new RedirectRenderer('login');

            $requiredFields = [
                'post_id' => ValueType::INT,
                'author_id' => ValueType::INT
            ];
            $validatedData = ValidationHelper::validateFields($requiredFields, $_POST);

            if($validatedData['author_id'] !== $authUser->getId()) throw new Exception('Invalid user!');
            
            $postDAO = DAOFactory::getPostDAO();
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
    'form/update/profile' => Route::create('form/update/profile', function(): HTTPRenderer {
        try {
            if($_SERVER['REQUEST_METHOD'] !== 'POST') throw new Exception('Invalid request method!');

            $requiredFields = [
                'username' => ValueType::STRING,
                'age' => ValueType::INT,
                'address' => ValueType::STRING,
                'hobby' => ValueType::STRING,
                'self_introduction' => ValueType::STRING
            ];

            $validatedData = ValidationHelper::validateFields($requiredFields, $_POST);
            
            $user = Authenticate::getAuthenticatedUser();
            $userId = $user->getId();
            
            $profileDAO = DAOFactory::getProfileDAO();
            $prevImagePath = $profileDAO->getImagePath($userId);
            
            $file = $_FILES['upload-file'];
            $imageService = null;
            $publicAuthUserImagePath = null;
            if(isset($file) && $file['error'] === UPLOAD_ERR_OK) {
                $validatedFileData = ValidationHelper::validateFile($file);
                $imageService = new ImageService(
                    type: $validatedFileData['type'],
                    tempPath: $file['tmp_name']
                );
                $publicAuthUserImagePath = $imageService->generatePublicImagePath();
            }

            $profile = new Profile(
                username: $validatedData['username'],
                userId: $userId,
                age: $validatedData['age'],
                imagePath: $publicAuthUserImagePath,
                address: $validatedData['address'],
                hobby: $validatedData['hobby'],
                selfIntroduction: $validatedData['self_introduction']
            );
            $success = $profileDAO->updateProfile($profile);
            if($success === false) throw new Exception('Failed to update profile!');

            if(isset($file) && $file['error'] === UPLOAD_ERR_OK) {
                $isSavedToDir = $imageService->saveToDir($publicAuthUserImagePath);
                if($isSavedToDir === false) throw new Exception('Failed to save to directory.');

                if($prevImagePath !== null) {
                    $isDeletePrevImageFromDir = $imageService->DeleteFromDir($prevImagePath);
                    if($isDeletePrevImageFromDir === false) throw new Exception('Failed to delete prev image path from the directory.');
                }
            }

            return new RedirectRenderer('profile?user=' . $validatedData['username']);
        } catch (\InvalidArgumentException $e) {
            error_log($e->getMessage());

            return new JSONRenderer(['status' => 'error']);
        } catch (Exception $e) {
            error_log($e->getMessage());

            return new RedirectRenderer('login');
        }
    })->setMiddleware(['auth']),
    'form/reply' => Route::create('form/reply', function(): HTTPRenderer {
        try {
            if($_SERVER['REQUEST_METHOD'] !== 'POST') throw new Exception('Invalid request method!');

            $user = Authenticate::getAuthenticatedUser();
            
            if($user === null) return new RedirectRenderer('login');
            $userId = $user->getId();

            $requiredFields = [
                'content' => ValueType::STRING,
                'parent_post_id' => ValueType::INT
            ];
            $validatedData = ValidationHelper::validateFields($requiredFields, $_POST);
            
            $file = $_FILES['upload-file'];
            $imageService = null;
            $publicPostImagePath = null;
            if(isset($file) && $file['error'] === UPLOAD_ERR_OK) {
                $imageService = new ImageService(
                    type: $file['type'],
                    tempPath: $file['tmp_name'],
                );
                $publicPostImagePath = $imageService->generatePublicImagePath();
            }

            $postDAO = DAOFactory::getPostDAO();
            $post = new Post(
                content: $validatedData['content'],
                imagePath: $publicPostImagePath,
                userId: $userId,
                parentPostId: $validatedData['parent_post_id']
            );
            
            $success = $postDAO->create($post);
            if($success === false) throw new Exception('Failed to create reply!');

            if(isset($file) && $file['error'] === UPLOAD_ERR_OK) {
                $isSavedToDir = $imageService->saveToDir($publicPostImagePath);
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
            $userId = $authUser->getId();

            $requiredFields = [
                'post_id' => ValueType::INT
            ];
            $validatedData = ValidationHelper::validateFields($requiredFields, $_POST);

            $likeDAO = DAOFactory::getLikeDAO();
            $like = new Like(
                userId: $userId,
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

            if ($validatedData['user1_id'] !== $authUser->getId()) {
                throw new Exception('Invalid user1_id — not matching authenticated user.');
            }

            if ($validatedData['user1_id'] === $validatedData['user2_id']) {
                throw new Exception('Cannot start a conversation with yourself.');
            }
            
            $userDAO = DAOFactory::getUserDAO();
            $isPartnerExists = $userDAO->getById($validatedData['user2_id']);
            if($isPartnerExists === null) throw new Exception('Partner is not exists.');

            $conversation = new Conversation(
                user1Id: $validatedData['user1_id'],
                user2Id: $validatedData['user2_id'],
            );
            $conversationDAO = DAOFactory::getConversationDAO();

            $isExistsConversation = $conversationDAO->existsByUserPair($conversation);
            if($isExistsConversation) throw new Exception('Conversation already exists.');

            $success = $conversationDAO->create($conversation);
            if($success === false) throw new Exception('Failed to create conversation.');

            return new RedirectRenderer('page/messages');
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
                'sender_id' => ValueType::INT,
                'content' => ValueType::STRING
            ];
            $validatedData = ValidationHelper::validateFields($requiredFields, $_POST);

            if($validatedData['sender_id'] !== $authUser->getId()) {
                throw new Exception('Cannot start a message with yourself');
            }

            // TODO: conversationの存在検証

            $directMessage = new DirectMessge(
                conversationId: $validatedData['conversation_id'],
                senderId: $validatedData['sender_id'],
                content: $validatedData['content']
            );
            $directMessageDAO = DAOFactory::getDirectMessage();
            $success = $directMessageDAO->create($directMessage);
            if($success === false) throw new Exception('Failed to create direct message.');
            
            return new JSONRenderer(['status' => 'Create direct message successfully']);
        } catch (\Exception $e) {
            error_log($e->getMessage());

            return new RedirectRenderer('login');
        }
    })->setMiddleware(['auth']),
];

