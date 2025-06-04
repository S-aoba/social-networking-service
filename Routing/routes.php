<?php

use Routing\Route;
use Auth\Authorizer;
use Auth\ConversationAuthorizer;
use Response\FlashData;
use Response\HTTPRenderer;
use Response\Render\HTMLRenderer;
use Response\Render\JSONRenderer;
use Response\Render\RedirectRenderer;
use Helpers\Authenticate;
use Helpers\ValidationHelper;
use Database\DataAccess\DAOFactory;
use Exceptions\AuthenticationFailureException;
use Models\Conversation;
use Models\DirectMessge;
use Models\File;
use Models\Like;
use Models\Notification;
use Models\Post;
use Models\Profile;
use Models\User;
use Services\Conversation\ConversationParnerResolver;
use Services\Image\ImagePathGenerator;
use Services\Image\ImagePathResolver;
use Services\Image\ImageStorage;
use Services\Image\ImageUrlBuilder;
use Validators\Validator;

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

    '' => Route::create('', function (): HTTPRenderer {
        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
                throw new Exception('Invalid request method!');
            }

            $authUser = Authenticate::getAuthenticatedUser();
            if ($authUser === null) {
                return new RedirectRenderer('login');
            }

            $profileDAO = DAOFactory::getProfileDAO();
            $authUserProfile = $profileDAO->getByUserId($authUser->getId());
            if ($authUserProfile === null) {
                return new RedirectRenderer('login');
            }

            $imageUrlBuilder = new ImageUrlBuilder();
            $imagePathResolver = new ImagePathResolver($imageUrlBuilder);

            $imagePathResolver->resolveProfile($authUserProfile);

            // TODO: フォロワータブとおすすめタブで取得するPostを変えるロジックにする
            $postDAO = DAOFactory::getPostDAO();
            $posts = $postDAO->getFollowingPosts($authUserProfile->getUserId());

            $imagePathResolver->resolveProfileMany($posts, 'author');
            $imagePathResolver->resolvePostMany($posts);

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
    'profile' => Route::create('profile', function (): HTTPRenderer {
        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
                throw new Exception('Invalid request method!');
            }

            $authUser = Authenticate::getAuthenticatedUser();
            if ($authUser === null) {
                return new RedirectRenderer('login');
            }

            $requiredFields = [
                'user' => 'required|string|exists:users,username'
            ];
            $validatedData = (new Validator($requiredFields))->validate($_GET);

            $profileDAO = DAOFactory::getProfileDAO();

            $authUserProfile = $profileDAO->getByUserId($authUser->getId());
            if ($authUserProfile === null) {
                return new RedirectRenderer('login');
            }

            $imageUrlBuilder = new ImageUrlBuilder();
            $imagePathResolver = new ImagePathResolver($imageUrlBuilder);

            $imagePathResolver->resolveProfile($validatedData['user']);
            $imagePathResolver->resolveProfile($authUserProfile);

            $postDAO = DAOFactory::getPostDAO();
            $posts = $postDAO->getByUserId($validatedData['user']->getUserId());
            $imagePathResolver->resolveProfileMany($posts, 'author');
            $imagePathResolver->resolvePostMany($posts);

            $followDAO = DAOFactory::getFollowDAO();
            $followerCount = $followDAO->getFollowerCount($validatedData['user']->getUserId());
            $followingCount = $followDAO->getFollowingCount($validatedData['user']->getUserId());
            $isFollow = $followDAO->isFollowingSelf($authUserProfile->getUserId(), $validatedData['user']->getUserId());

            return new HTMLRenderer('page/profile', [
                'isFollow' => $isFollow,
                'authUser' => $authUserProfile,
                'queryUser' => $validatedData['user'],
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
    'post' => Route::create('post', function (): HTTPRenderer {
        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
                throw new Exception('Invalid request method!');
            }

            $authUser = Authenticate::getAuthenticatedUser();
            if ($authUser === null) {
                return new RedirectRenderer('login');
            }

            $profileDAO = DAOFactory::getProfileDAO();
            $authUserProfile = $profileDAO->getByUserId($authUser->getId());
            if ($authUserProfile === null) {
                return new RedirectRenderer('login');
            }

            $imageUrlBuilder = new ImageUrlBuilder();
            $imagePathResolver = new ImagePathResolver($imageUrlBuilder);

            $imagePathResolver->resolveProfile($authUserProfile);

            $requiredFields = [
                'id' => 'required|int|exists:posts,id'
            ];
            $validatedData = (new Validator($requiredFields))->validate($_GET);

            $postDAO = DAOFactory::getPostDAO();

            $imagePathResolver->resolvePost($validatedData['id']['post']);
            $imagePathResolver->resolveProfile($validatedData['id']['author']);

            $replies = $postDAO->getReplies(
                $validatedData['id']['post']->getId(),
                $authUserProfile->getUserId()
            );
            $imagePathResolver->resolveProfileMany(
                $replies,
                'author'
            );
            $imagePathResolver->resolvePostMany($replies);

            return new HTMLRenderer('page/post', [
                'authUser' => $authUserProfile,
                'data' => $validatedData['id'],
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
    'following' => Route::create('following', function (): HTTPRenderer {
        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
                throw new Exception('Invalid request method!');
            }

            $authUser = Authenticate::getAuthenticatedUser();
            if ($authUser === null) {
                return new RedirectRenderer('login');
            }

            $profileDAO = DAOFactory::getProfileDAO();
            $authUserProfile = $profileDAO->getByUserId($authUser->getId());
            if ($authUserProfile === null) {
                return new RedirectRenderer('login');
            }

            $imageUrlBuilder = new ImageUrlBuilder();
            $imagePathResolver = new ImagePathResolver($imageUrlBuilder);

            $imagePathResolver->resolveProfile($authUserProfile);

            $followDAO = DAOFactory::getFollowDAO();
            $following = $followDAO->getFollowing($authUserProfile->getUserId());
            $imagePathResolver->resolveProfileMany($following, null);

            return new HTMLRenderer('page/following', [
                'authUser' => $authUserProfile,
                'data' => $following,
            ]);
        } catch (\Exception $e) {
            error_log($e->getMessage());
            return new RedirectRenderer('login');
        }
    })->setMiddleware(['auth']),
    'follower' => Route::create('follower', function (): HTTPRenderer {
        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
                throw new Exception('Invalid request method!');
            }

            $authUser = Authenticate::getAuthenticatedUser();
            if ($authUser === null) {
                return new RedirectRenderer('login');
            }

            $profileDAO = DAOFactory::getProfileDAO();
            $authUserProfile = $profileDAO->getByUserId($authUser->getId());
            if ($authUserProfile === null) {
                return new RedirectRenderer('login');
            }

            $imageUrlBuilder = new ImageUrlBuilder();
            $imagePathResolver = new ImagePathResolver($imageUrlBuilder);
            $imagePathResolver->resolveProfile($authUserProfile);

            $followDAO = DAOFactory::getFollowDAO();
            $followers = $followDAO->getFollower($authUserProfile->getUserId());
            $imagePathResolver->resolveProfileMany($followers, null);

            return new HTMLRenderer('page/follower', [
                'authUser' => $authUserProfile,
                'data' => $followers,
            ]);
        } catch (\Exception $e) {
            error_log($e->getMessage());
            return new RedirectRenderer('login');
        }
    })->setMiddleware(['auth']),
    'messages' => Route::create('message', function (): HTTPRenderer {
        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
                throw new Exception('Invalid request method!');
            }

            $authUser = Authenticate::getAuthenticatedUser();
            if ($authUser === null) {
                return new RedirectRenderer('login');
            }

            $profileDAO = DAOFactory::getProfileDAO();
            $authUserProfile = $profileDAO->getByUserId($authUser->getId());
            if ($authUserProfile === null) {
                return new RedirectRenderer('login');
            }

            $imageUrlBuilder = new ImageUrlBuilder();
            $imagePathResolver = new ImagePathResolver($imageUrlBuilder);

            $imagePathResolver->resolveProfile($authUserProfile);

            $conversationDAO = DAOFactory::getConversationDAO();
            $conversations = $conversationDAO->findAllByUserId($authUser->getId());

            $imagePathResolver->resolveProfileMany($conversations, 'partner');

            $followDAO = DAOFactory::getFollowDAO();
            $followers = $followDAO->getFollower($authUserProfile->getUserId());
            $imagePathResolver->resolveProfileMany($followers, null);

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
    'message' => Route::create('message', function (): HTTPRenderer {
        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
                throw new Exception('Invalid request method!');
            }

            $authUser = Authenticate::getAuthenticatedUser();
            if ($authUser === null) {
                return new RedirectRenderer('login');
            }

            $profileDAO = DAOFactory::getProfileDAO();
            $authUserProfile = $profileDAO->getByUserId($authUser->getId());
            if ($authUserProfile === null) {
                return new RedirectRenderer('login');
            }

            $requiredFields = [
                'id' => 'required|int|exists:conversations,id'
            ];
            $validatedData = (new Validator($requiredFields))->validate($_GET);

            $conversationDAO = DAOFactory::getConversationDAO();

            $conversationParnerResolver = new ConversationParnerResolver($profileDAO);
            $partnerProfile = $conversationParnerResolver->resolverPartnerProfile(
                $authUserProfile->getUserId(),
                $validatedData['id']
            );
            if ($partnerProfile === null) {
                return new RedirectRenderer('login');
            }

            $imageUrlBuilder = new ImageUrlBuilder();

            $imagePathResolver = new ImagePathResolver($imageUrlBuilder);
            $imagePathResolver->resolveProfile($authUserProfile);
            $imagePathResolver->resolveProfile($partnerProfile);

            $conversations = $conversationDAO->findAllByUserId($authUser->getId());
            $imagePathResolver->resolveProfileMany(
                $conversations,
                'partner'
            );

            $directMessageDAO = DAOFactory::getDirectMessage();
            $directMessages = $directMessageDAO->getAllByConversationId($validatedData['id']->getId());
            $imagePathResolver->resolveDirectMessageMany($directMessages);

            $followDAO = DAOFactory::getFollowDAO();
            $followers = $followDAO->getFollower($authUserProfile->getUserId());
            $imagePathResolver->resolveProfileMany($followers, null);

            return new HTMLRenderer('page/message', [
                'conversation' => $validatedData['id'],
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
    'notification' => Route::create('notification', function (): HTTPRenderer {
        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
                throw new Exception('Invalid request method!');
            }

            $authUser = Authenticate::getAuthenticatedUser();
            if ($authUser === null) {
                return new RedirectRenderer('login');
            }

            $profileDAO = DAOFactory::getProfileDAO();
            $authUserProfile = $profileDAO->getByUserId($authUser->getId());
            if ($authUserProfile === null) {
                return new RedirectRenderer('login');
            }

            $imageUrlBuilder = new ImageUrlBuilder();
            $imagePathResolver = new ImagePathResolver($imageUrlBuilder);

            $imagePathResolver->resolveProfile($authUserProfile);

            return new HTMLRenderer('page/notification', [
                'authUser' => $authUserProfile
            ]);
        } catch (\Exception $e) {
            error_log($e->getMessage());

            return new RedirectRenderer('login');
        }
    })->setMiddleware(['auth']),

    // Auth
    'api/login' => Route::create('api/login', function (): HTTPRenderer {
        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                throw new Exception('Invalid request method!');
            }

            $requiredFields = [
                'email' => 'required|email',
                'password' => 'required|password',
            ];

            $validatedData = (new Validator($requiredFields))->validate($_POST);

            Authenticate::authenticate($validatedData['email'], $validatedData['password']);

            // TODO: message-boxesを作成したら使用する
            // FlashData::setFlashData('success', 'Logged in successfully.');
            return new JSONRenderer([
                'status' => 'success',
                'message' => 'Logged in successfully.',
                'redirect' => ''
            ]);
        } catch (AuthenticationFailureException $e) {
            error_log($e->getMessage());

            return new JSONRenderer([
                'status' => 'error',
                'message' => 'Failed to login, wrong email and/or password.'
            ]);
        } catch (\InvalidArgumentException $e) {
            error_log($e->getMessage());

            return new JSONRenderer([
                'status' => 'error',
                'message' => json_decode($e->getMessage())
            ]);
        } catch (Exception $e) {
            error_log($e->getMessage());

            return new JSONRenderer([
                'status' => 'error',
                'message' => 'An error occurred.'
            ]);
        }
    })->setMiddleware(['guest']),
    'api/register' => Route::create('api/register', function (): HTTPRenderer {
        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                throw new Exception('Invalid request method!');
            }

            $requiredFields = [
                'username' => 'required|string|min:1|max:20',
                'email' => 'required|email',
                'password' => 'required|password',
                'confirm_password' => 'required|password'
            ];

            $validatedData = (new Validator($requiredFields))->validate($_POST);

            if ($validatedData['confirm_password'] !== $validatedData['password']) {
                return new JSONRenderer([
                    'status' => 'error',
                    'message' => 'Invalid Password!'
                ]);
            }

            $userDao = DAOFactory::getUserDAO();
            if ($userDao->getByEmail($validatedData['email'])) {
                return new JSONRenderer([
                    'status' => 'error',
                    'message' => 'Email is already in use!'
                ]);
            }

            $user = new User(
                email: $validatedData['email'],
            );

            $success = $userDao->create($user, $validatedData['password']);

            if (!$success) {
                throw new Exception('Failed to create new user!');
            }

            $profileDAO = DAOFactory::getProfileDAO();
            $profile = new Profile(
                username: $validatedData['username'],
                userId: $user->getId()
            );

            $success = $profileDAO->create($profile);
            // もし失敗した場合、User情報のみがDB上に残るのでロールバック機能を付けることが必要
            if (!$success) {
                throw new Exception('Failed to create new profile');
            }

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

            // FlashData::setFlashData('success', 'Account successfully created.');
            return new JSONRenderer([
                'status' => 'success',
                // TODO: '' routeを'home'に変更したらこちらも変更
                'redirect' => ''
            ]);
        } catch (\InvalidArgumentException $e) {
            error_log($e->getMessage());

            return new JSONRenderer([
                'status' => 'error',
                'message' => json_decode($e->getMessage())
            ]);
        } catch (Exception $e) {
            error_log($e->getMessage());

            return new JSONRenderer([
                'status' => 'error',
                'message' => 'An error occurred.'
            ]);
        }
    })->setMiddleware(['guest']),

    // Create
    'api/post' => Route::create('api/post', function (): HTTPRenderer {
        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                throw new Exception('Invalid request method!');
            }

            $authUser = Authenticate::getAuthenticatedUser();
            if ($authUser === null) {
                return new RedirectRenderer('login');
            }

            $requiredFields = [
                'content' => 'required|string|min:1|max:144'
            ];
            $validatedData = (new Validator($requiredFields))->validate($_POST);

            $file = File::fromArray($_FILES['upload-file']);
            $publicPostImagePath = null;
            if ($file->isValid()) {
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
            if ($success === false) {
                throw new Exception('Failed Create Post');
            }

            if ($file->isValid()) {
                $imageStorage = new ImageStorage();
                $isSavedToDir = $imageStorage->save($publicPostImagePath, $validatedFileData->getTmpName());
                if ($isSavedToDir === false) {
                    throw new Exception('Failed to save to directory.');
                }
            }

            return new JSONRenderer([
                'status' => 'success'
            ]);
        } catch (\InvalidArgumentException $e) {
            error_log($e->getMessage());

            return new JSONRenderer([
                'status' => 'error',
                'message' => json_decode($e->getMessage())
            ]);
        } catch (\Exception $e) {
            error_log($e->getMessage());

            return new JSONRenderer([
                'status' => 'error',
                'message' => 'An error occurred.'
            ]);
        }
    })->setMiddleware(['auth']),
    'api/follow' => Route::create('api/follow', function (): HTTPRenderer {
        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                throw new Exception('Invalid request method!');
            }

            $authUser = Authenticate::getAuthenticatedUser();
            if ($authUser === null) {
                return new RedirectRenderer('login');
            }
            $userId = $authUser->getId();

            $requiredFields = [
                'following_id' => 'required|int'
            ];

            $validatedData = (new Validator($requiredFields))->validate($_POST);

            $followDAO = DAOFactory::getFollowDAO();
            $isFollow = $followDAO->isFollowingSelf($userId, $validatedData['following_id']);
            $success = $isFollow ?
                        $followDAO->unfollow($userId, $validatedData['following_id'])
                        :
                        $followDAO->follow($userId, $validatedData['following_id']);

            if (!$success) {
                throw new Exception('Failed follow');
            }

            if($isFollow === false) {
                $profileDAO = DAOFactory::getProfileDAO();
                $profile = $profileDAO->getByUserId($authUser->getId());
                $data = [
                        'message' => "{$profile->getUsername()}さんにフォローされました。"
                ];
    
                $notification = new Notification(
                    userId: $authUser->getId(),
                    type: 'follow',
                    data: json_encode($data),
                );
    
                $notificationDAO = DAOFactory::getNotificationDAO();
                $success = $notificationDAO->notifyUser($notification);
                if($success === false) throw new Exception('Failed to create notification.');
            }

            return new JSONRenderer(['status' => 'success']);
        } catch (\InvalidArgumentException $e) {
            error_log($e->getMessage());

            return new JSONRenderer([
                'status' => 'error',
                'message' => 'An error occurred.'
            ]);
        } catch (\Exception $e) {
            error_log($e->getMessage());

            return new JSONRenderer([
                'status' => 'error',
                'message' => 'An error occurred.'
            ]);
        }
    })->setMiddleware(['auth']),
    'api/reply' => Route::create('api/reply', function (): HTTPRenderer {
        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                throw new Exception('Invalid request method!');
            }

            $authUser = Authenticate::getAuthenticatedUser();
            if ($authUser === null) {
                return new RedirectRenderer('login');
            }

            $requiredFields = [
                'content' => 'required|string|min:1|max:144',
                'parent_post_id' => 'required|int|exists:posts,id'
            ];
            $validatedData = (new Validator($requiredFields))->validate($_POST);

            $publicPostImagePath = null;

            $file = File::fromArray($_FILES['upload-file']);
            if ($file->isValid()) {
                $validatedFileData = ValidationHelper::validateFile($file);
                $publicPostImagePath = (new ImagePathGenerator())->generate($validatedFileData->getTypeWithoutPrefix());
            }

            $postDAO = DAOFactory::getPostDAO();
            $parentPost = $postDAO->findParentPost($validatedData['parent_post_id']['post']->getId());
            if ($parentPost === null) {
                throw new Exception('Parent post is not exits.');
            }

            $post = new Post(
                content: $validatedData['content'],
                imagePath: $publicPostImagePath,
                userId: $authUser->getId(),
                parentPostId: $parentPost->getId()
            );

            $success = $postDAO->create($post);
            if ($success === false) {
                throw new Exception('Failed to create reply!');
            }

            if ($file->isValid()) {
                $imageStorage = new ImageStorage();
                $isSavedToDir = $imageStorage->save(
                    $publicPostImagePath,
                    $validatedFileData->getTmpName()
                );
                if ($isSavedToDir === false) {
                    throw new Exception('Failed to save to directory.');
                }
            }

            if($authUser->getId() !== $parentPost->getUserId()) {
                $profileDAO = DAOFactory::getProfileDAO();
                $profile = $profileDAO->getByUserId($authUser->getId());
                $data = json_encode([
                    'message' => "{$profile->getUsername()}さんから返信がありました。",
                    'redirect' => "/post?id={$parentPost->getId()}"
                ]);
                $notification = new Notification(
                    userId: $authUser->getId(),
                    type: 'reply',
                    data: $data
                );
                $notificationDAO = DAOFactory::getNotificationDAO();
                $success = $notificationDAO->notifyUser($notification);
                if($success === false) throw new Exception('Failed to create notification.');
            }

            return new JSONRenderer([
                'status' => 'success'
            ]);
        } catch (\InvalidArgumentException $e) {
            error_log($e->getMessage());

            return new JSONRenderer([
                'status' => 'error',
                'message' => json_decode($e->getMessage())
            ]);
        } catch (Exception $e) {
            error_log($e->getMessage());

            return new JSONRenderer([
                'status' => 'error',
                'message' => 'An error occurred.'
            ]);
        }
    })->setMiddleware(['auth']),
    'api/like' => Route::create('api/like', function (): HTTPRenderer {
        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                throw new Exception('Invalid request method!');
            }

            $authUser = Authenticate::getAuthenticatedUser();
            if ($authUser === null) {
                return new RedirectRenderer('login');
            }

            $requiredFields = [
                'post_id' => 'required|int|exists:posts,id'
            ];
            $validatedData = (new Validator($requiredFields))->validate($_POST);

            $likeDAO = DAOFactory::getLikeDAO();
            $like = new Like(
                userId: $authUser->getId(),
                postId: $validatedData['post_id']['post']->getId()
            );

            $isLiked = $likeDAO->hasLiked($like);

            $success = $isLiked ? $likeDAO->unlike($like) : $likeDAO->like($like);
            if ($success === false) {
                throw new Exception('Failed to like post!');
            }

            $likeCount = $likeDAO->getLikeCount($like);

            return new JSONRenderer([
                'status' => 'success',
                'liked' => $isLiked,
                'likeCount' => $likeCount
            ]);
        } catch (\InvalidArgumentException $e) {
            error_log($e->getMessage());

            return new JSONRenderer([
                'status' => 'error',
                'message' => 'An error occurred.'
            ]);
        } catch (Exception $e) {
            error_log($e->getMessage());

            return new JSONRenderer([
                'status' => 'error',
                'message' => 'An error occurred.'
            ]);
        }
    })->setMiddleware(['auth']),
    'api/conversation' => Route::create('api/conversation', function (): HTTPRenderer {
        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                throw new Exception('Invalid request method!');
            }

            $authUser = Authenticate::getAuthenticatedUser();
            if ($authUser === null) {
                return new RedirectRenderer('login');
            }

            $requiredFields = [
                'user1_id' => 'required|int',
                'user2_id' => 'required|int|exists:users,id'
            ];
            $validatedData = (new Validator($requiredFields))->validate($_POST);

            $conversationAuthorizer = new ConversationAuthorizer();

            if ($conversationAuthorizer->isSameId($validatedData['user1_id'], $authUser->getId()) === false) {
                throw new Exception('Invalid user1_id — not matching authenticated user.');
            }

            if ($conversationAuthorizer->isSameId(
                $validatedData['user1_id'],
                $validatedData['user2_id']->getUserId()
            )) {
                throw new Exception('Cannot start a conversation with yourself.');
            }

            $followDAO = DAOFactory::getFollowDAO();
            $isMutualFollow = $conversationAuthorizer->isMutualFollow(
                $followDAO,
                $authUser->getId(),
                $validatedData['user2_id']->getUserId()
            );

            if ($isMutualFollow === false) {
                throw new Exception('AuthUser and PartnerUser is not mutualFollow.');
            }

            $conversation = new Conversation(
                user1Id: $authUser->getId(),
                user2Id: $validatedData['user2_id']->getUserId(),
            );
            $conversationDAO = DAOFactory::getConversationDAO();

            $isExistsConversation = $conversationDAO->hasConversationWith($conversation);
            if ($isExistsConversation) {
                throw new Exception('Conversation already exists.');
            }

            $success = $conversationDAO->create($conversation);
            if ($success === false) {
                throw new Exception('Failed to create conversation.');
            }

            return new JSONRenderer([
                'status' => 'success',
                'redirect' => 'message?id=' . $conversation->getId()
            ]);
        } catch (\InvalidArgumentException $e) {
            error_log($e->getMessage());

            return new JSONRenderer([
                'status' => 'error',
                'message' => json_decode($e->getMessage())
            ]);
        } catch (\Exception $e) {
            error_log($e->getMessage());

            return new JSONRenderer([
                'status' => 'errror',
                'message' => 'An error occurred.'
            ]);
        }
    })->setMiddleware(['auth']),
    'api/direct-message' => Route::create('api/direct-message', function (): HTTPRenderer {
        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                throw new Exception('Invalid request method!');
            }

            $authUser = Authenticate::getAuthenticatedUser();
            if ($authUser === null) {
                return new RedirectRenderer('login');
            }

            $requiredFields = [
                'conversation_id' => 'required|int|exists:conversations,id',
                'content' => 'required|string|min:1|max:144'
            ];
            $validatedData = (new Validator($requiredFields))->validate($_POST);

            $file = File::fromArray($_FILES['upload-file']);
            $publicMessageImagePath = null;
            if ($file->isValid()) {
                $validatedFileData = ValidationHelper::validateFile($file);
                $publicMessageImagePath = (new ImagePathGenerator())->generate($validatedFileData->getTypeWithoutPrefix());
            }

            $conversationAuthorizer = new ConversationAuthorizer();
            $isJoinTheConversation = $conversationAuthorizer->isJoin(
                $authUser->getId(),
                $validatedData['conversation_id']
            );
            if ($isJoinTheConversation === false) {
                throw new Exception('Cannnot the action.');
            }

            $profileDAO = DAOFactory::getProfileDAO();
            $partnerProfile = $conversationAuthorizer->isExistsPartnerUser(
                $authUser->getId(),
                $validatedData['conversation_id'],
                $profileDAO
            );
            if ($partnerProfile === false) {
                throw new Exception('Partner User is not exists.');
            }

            $directMessage = new DirectMessge(
                conversationId: $validatedData['conversation_id']->getId(),
                senderId: $authUser->getId(),
                content: $validatedData['content'],
                imagePath: $publicMessageImagePath
            );

            $directMessageDAO = DAOFactory::getDirectMessage();
            $success = $directMessageDAO->create($directMessage);
            if ($success === false) {
                throw new Exception('Failed to create direct message.');
            }

            if ($file->isValid()) {
                $imageStorage = new ImageStorage();
                $imageStorage->save($publicMessageImagePath, $validatedFileData->getTmpName());
            }

            return new JSONRenderer([
                'status' => 'success',
                'redirect' => 'message?id=' . $validatedData['conversation_id']->getId()
            ]);
        } catch (\InvalidArgumentException $e) {
            error_log($e->getMessage());

            return new JSONRenderer([
                'status' => 'error',
                'message' => json_decode($e->getMessage())
            ]);
        } catch (\Exception $e) {
            error_log($e->getMessage());

            return new JSONRenderer([
                'status' => 'error',
                'message' => 'An error occurred.'
            ]);
        }
    })->setMiddleware(['auth']),

    // Update
    'api/profile' => Route::create('api/profile', function (): HTTPRenderer {
        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                throw new Exception('Invalid request method!');
            }

            $authUser = Authenticate::getAuthenticatedUser();
            if ($authUser === null) {
                return new RedirectRenderer('login');
            }

            $requiredFields = [
                'username' => 'required|string|min:1|max:20',
                'age' => 'int|min:1|max:100',
                'address' => 'string|min:0|max:100',
                'hobby' => 'string|min:0|max:100',
                'self_introduction' => 'string|min:0|max:500'
            ];
            $validatedData = (new Validator($requiredFields))->validate($_POST);

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
            if ($success === false) {
                throw new Exception('Failed to update profile!');
            }

            return new JSONRenderer([
                'status' => 'success',
                'redirect' => 'profile?user=' . $validatedData['username']
            ]);
        } catch (\InvalidArgumentException $e) {
            error_log($e->getMessage());

            return new JSONRenderer([
                'status' => 'error',
                'message' => json_decode($e->getMessage())
            ]);
        } catch (Exception $e) {
            error_log($e->getMessage());

            return new JSONRenderer([
                'status' => 'error',
                'message' => 'An error occurred.'
            ]);
        }
    })->setMiddleware(['auth']),
    'api/profile/icon' => Route::create('api/profile/icon', function (): HTTPRenderer {
        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                throw new Exception('Invalid request method!');
            }

            $authUser = Authenticate::getAuthenticatedUser();
            if ($authUser === null) {
                return new RedirectRenderer('login');
            }

            $file = File::fromArray($_FILES['upload-file']);
            if ($file->isValid() === false) {
                return new JSONRenderer(['status' => 'File upload is invalid.']);
            }

            $validatedFileData = ValidationHelper::validateFile($file);

            $publicProfileIconPath = (new ImagePathGenerator())->generate($validatedFileData->getTypeWithoutPrefix());

            $profileDAO = DAOFactory::getProfileDAO();
            $profile = $profileDAO->getByUserId($authUser->getId());
            $prevProfileImagePath = $profile->getImagePath();
            if ($profile === null) {
                return new RedirectRenderer('login');
            }

            $success = $profileDAO->updataPrpfileIcon($publicProfileIconPath, $authUser->getId());
            if ($success === false) {
                throw new Exception('Failed to update profile!');
            }

            $imageStorage = new ImageStorage();
            $imageStorage->save($publicProfileIconPath, $validatedFileData->getTmpName());

            if ($prevProfileImagePath !== null) {
                $imageStorage->delete($prevProfileImagePath);
            }

            return new JSONRenderer([
                'status' => 'success',
                'redirect' => 'profile?user=' . $profile->getUsername()
            ]);
        } catch (\InvalidArgumentException $e) {
            error_log($e->getMessage());

            return new JSONRenderer([
                'status' => 'error',
                'message' => 'Invalid error.'
            ]);
        } catch (Exception $e) {
            error_log($e->getMessage());

            return new JSONRenderer([
                'status' => 'error',
                'message' => 'An error occurred.'
            ]);
        }
    })->setMiddleware(['auth']),

    // Delete
    'api/delete/post' => Route::create('api/delete/post', function (): HTTPRenderer {
        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                throw new Exception('Invalid request method!');
            }

            $authUser = Authenticate::getAuthenticatedUser();
            if ($authUser === null) {
                return new RedirectRenderer('login');
            }

            $requiredFields = [
                'post_id' => 'required|int|exists:posts,id'
            ];
            $validatedData = (new Validator($requiredFields))->validate($_POST);

            $postDAO = DAOFactory::getPostDAO();

            $post = $validatedData['post_id']['post'];
            if (Authorizer::isOwnedByUser($post->getUserId(), $authUser->getId(), ) === false) {
                throw new Exception('Cannnot the action.');
            }

            $success = $postDAO->deletePost($post->getId());
            if ($success === false) {
                throw new Exception('Failed to delete post!');
            }

            return new JSONRenderer([
                'status' => 'success'
            ]);
        } catch (\InvalidArgumentException $e) {
            error_log($e->getMessage());

            return new JSONRenderer([
                'status' => 'error',
                'message' => json_decode($e->getMessage())
            ]);
        } catch (\Exception $e) {
            error_log($e->getMessage());

            return new JSONRenderer([
                'status' => 'error',
                'message' => 'An error occurred.'
            ]);
        }
    })->setMiddleware(['auth']),
    'api/delete/conversation' => Route::create('api/delete/conversation', function (): HTTPRenderer {
        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                throw new Exception('Invalid request method!');
            }

            $authUser = Authenticate::getAuthenticatedUser();
            if ($authUser === null) {
                return new RedirectRenderer('login');
            }

            $requiredFields = [
                'conversation_id' => 'required|int|exists:conversations,id'
            ];
            $validatedData = (new Validator($requiredFields))->validate($_POST);

            $conversationDAO = DAOFactory::getConversationDAO();
            $conversation = $validatedData['conversation_id'];

            if (Authorizer::isOwnedByUser($conversation->getUser1Id(), $authUser->getId()) === false) {
                throw new Exception('Cannnot the action.');
            }

            $success = $conversationDAO->delete($conversation->getId());
            if ($success === false) {
                throw new Exception('Failed to delete conversation.');
            }

            return new JSONRenderer([
                'status' => 'success',
                // TODO: 参加している他のconversationが存在していれば、そのページに遷移する.なければ、messages
                'redirect' => 'messages'
            ]);
        } catch (\InvalidArgumentException $e) {
            error_log($e->getMessage());

            return new JSONRenderer([
                'status' => 'error',
                'message' => json_decode($e->getMessage())
            ]);
        } catch (\Exception $e) {
            error_log($e->getMessage());

            return new JSONRenderer([
                'status' => 'error',
                'message' => 'An error occurred.'
            ]);
        }
    })->setMiddleware(['auth']),
];
