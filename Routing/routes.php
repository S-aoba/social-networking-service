<?php

use Database\DataAccess\DAOFactory;
use Helpers\Authenticate;
use Helpers\ValidationHelper;
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
    '' => Route::create('', function(): HTTPRenderer {
        try {
            $user = Authenticate::getAuthenticatedUser();
    
            if($user === null) return new RedirectRenderer('login');
    
            $profileDAO = DAOFactory::getProfileDAO();
            $profile = $profileDAO->getByUserId($user->getId());
    
            $postDAO = DAOFactory::getPostDAO();
            $followerPosts = $postDAO->getFollowingPosts($user->getId());
    
            return new HTMLRenderer('page/home', [
                'profile' => $profile,
                'followerPosts' => $followerPosts,
            ]);
        } catch (\Exception $e) {
            error_log($e->getMessage());

            return new RedirectRenderer('login');
        }
    })->setMiddleware(['auth']),
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
    'profile' => Route::create('profile', function(): HTTPRenderer {
        try {
            $username = $_GET['user'];
            // TODO: do validation
            
            $user = Authenticate::getAuthenticatedUser();

            if($user === null) return new RedirectRenderer('login');

            $profileDAO = DAOFactory::getProfileDAO();
            $profile = $profileDAO->getByUsername($username);

            $postDAO = DAOFactory::getPostDAO();
            $posts = $postDAO->getByUserId($profile->getUserId());

            $followDAO = DAOFactory::getFollowDAO();
            $followerCount = $followDAO->getFollowerCount($profile->getUserId());
            $followingCount = $followDAO->getFollowingCount($profile->getUserId());

            $isFollow = $followDAO->checkIsFollow($user->getId(), $profile->getUserId());

            return new HTMLRenderer('page/profile', [
                'isFollow' => $isFollow,
                'loginedUserId' => $user->getId(),
                'profile' => $profile,
                'posts' => $posts,
                'followerCount' => $followerCount,
                'followingCount' => $followingCount,
            ]);
        } catch (\Exception $e) {
            error_log($e->getMessage());

            return new JSONRenderer(['status' => 'error']);
        }

    })->setMiddleware(['auth']),
    'post' => Route::create('post', function(): HTTPRenderer {
        try {
            $postId = $_GET['id'];

            // TODO: do validation
            $user = Authenticate::getAuthenticatedUser();
    
            if($user === null) return new RedirectRenderer('login');
    
            $profileDAO = DAOFactory::getProfileDAO();
            $profile = $profileDAO->getByUserId($user->getId());

            $postDAO = DAOFactory::getPostDAO();
            $post = $postDAO->getById(intval($postId), intval($user->getId()));

            if($post === null) throw new Exception('Post not found!');

            $replies = $postDAO->getReplies($postId, intval($user->getId()));

            return new HTMLRenderer('page/post', [
                'profile' => $profile,
                'data' => $post,
                'replies' => $replies,
            ]);
        } catch (\Exception $e) {
            error_log($e->getMessage());

            return new RedirectRenderer('login');
        }

    })->setMiddleware(['auth']),
    'following' => Route::create('following', function(): HTTPRenderer {
        try {
            $user = Authenticate::getAuthenticatedUser();
    
            if($user === null) return new RedirectRenderer('login');
    
            $profileDAO = DAOFactory::getProfileDAO();
            $profile = $profileDAO->getByUserId($user->getId());

            $followDAO = DAOFactory::getFollowDAO();
            $following = $followDAO->getFollowing($profile->getUserId());
            if($following === null) throw new Exception('Following not found!');

            return new HTMLRenderer('page/following', [
                'userId'  => $profile->getUserId(),
                'username' => $profile->getUsername(), 
                'imagePath' => $profile->getImagePath(),
                'data' => $following,
            ]);
        } catch (\Exception $e) {
            error_log($e->getMessage());
            return new RedirectRenderer('login');
        }
    })->setMiddleware(['auth']),
    'follower' => Route::create('follower', function(): HTTPRenderer {
        try {
            $user = Authenticate::getAuthenticatedUser();
    
            if($user === null) return new RedirectRenderer('login');
    
            $profileDAO = DAOFactory::getProfileDAO();
            $profile = $profileDAO->getByUserId($user->getId());

            $followDAO = DAOFactory::getFollowDAO();
            $follower = $followDAO->getFollower($profile->getUserId());
            if($follower === null) throw new Exception('Follower not found!');

            return new HTMLRenderer('page/follower', [
                'userId'  => $profile->getUserId(),
                'username' => $profile->getUsername(), 
                'imagePath' => $profile->getImagePath(),
                'data' => $follower,
            ]);
        } catch (\Exception $e) {
            error_log($e->getMessage());
            return new RedirectRenderer('login');
        }
    })->setMiddleware(['auth']),

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
            $content = $_POST['content'];
            $parentPostId = $_POST['parent_post_id'] === '' ? null : $_POST['parent_post_id'];

            $request = [
                'content' => $content,
                'userId' => $userId,
                'parentPostId' => $parentPostId
            ];
            
            // TODO: do validatoin

            $post = new Post(...$request);

            $postDAO = DAOFactory::getPostDAO();

            $success = $postDAO->create($post);

            if($success === false) throw new Exception('Failed Create Post');

            return new RedirectRenderer('');
        } catch (\Exception $e) {
            error_log($e->getMessage());

            FlashData::setFlashData('error', 'An error occurred.');

            // TODO: Change redirect route to error page or login page.
            return new RedirectRenderer('login');
        }
    })->setMiddleware(['auth']),
    'form/follow' => Route::create('form/follow', function(): HTTPRenderer {
        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') throw new Exception('Invalid request method!');

            $user = Authenticate::getAuthenticatedUser();

            if($user === null) return new RedirectRenderer('login');

            $userId = $user->getId();
            $followingId = $_POST['following_id'];
            

            // TODO: do validation
            $followDAO = DAOFactory::getFollowDAO();

            $isFollow = $followDAO->checkIsFollow($userId, $followingId);
            $success = $isFollow ? $followDAO->unfollow($userId, $followingId) : $followDAO->follow($userId, $followingId);

            if(!$success) throw new Exception('Failed follow');

            return new JSONRenderer(['status' => 'success']);
        } catch (\Exception $e) {
            error_log($e->getMessage());
            FlashData::setFlashData('error', 'An error occurred.');

            return new JSONRenderer(['status' => 'error']);
        }    
    })->setMiddleware(['auth']),
    'form/delete/post' => Route::create('form/delete/post', function(): HTTPRenderer {
        try {
            if($_SERVER['REQUEST_METHOD'] !== 'POST') throw new Exception('Invalid request method!');
            $userId = $_POST['user_id'];
            $postId = $_POST['post_id'];

            // TODO: do validation

            $user = Authenticate::getAuthenticatedUser();
            
            if(intval($userId) !== $user->getId()) throw new Exception('Invalid user!');
            $postDAO = DAOFactory::getPostDAO();
            $success = $postDAO->deletePost($postId);
            if($success === false) throw new Exception('Failed to delete post!');

            return new RedirectRenderer('');
        } catch (\Exception $e) {
            error_log($e->getMessage());

            return new RedirectRenderer('login');
        }
    })->setMiddleware(['auth']),
    'form/update/profile' => Route::create('form/update/profile', function(): HTTPRenderer {
        try {
            if($_SERVER['REQUEST_METHOD'] !== 'POST') throw new Exception('Invalid request method!');

            
            $username = $_POST['username'];
            $age = $_POST['age'];
            $address = $_POST['address'];
            $hobby = $_POST['hobby'];
            $selfIntroduction = $_POST['self_introduction'];
            
            $file = $_FILES['upload-file'];

            $userId = $_POST['user_id'];
        
            // TODO: do validation
            $user = Authenticate::getAuthenticatedUser();
            if(intval($userId) !== $user->getId()) throw new Exception('Invalid user!');
            
            $profileDAO = DAOFactory::getProfileDAO();
            // $prevImagePath = $profileDAO->getImagePath($userId);

            $profile = new Profile(
                username: $username,
                userId: $userId,
                imagePath: $fullImagePath,
                address: $address,
                age: $age,
                hobby: $hobby,
                selfIntroduction: $selfIntroduction,
            );
            $success = $profileDAO->updateProfile($profile);
            if($success === false) throw new Exception('Failed to update profile!');

            return new RedirectRenderer('profile?user=' . $username);
        } catch (Exception $e) {
            error_log($e->getMessage());

            return new RedirectRenderer('login');
        }
    })->setMiddleware(['auth']),
    'form/reply' => Route::create('form/reply', function(): HTTPRenderer {
        try {
            if($_SERVER['REQUEST_METHOD'] !== 'POST') throw new Exception('Invalid request method!');

            $content = $_POST['content'];
            $parentPostId = intval($_POST['parent_post_id']);
            
            // TODO: do validation

            $user = Authenticate::getAuthenticatedUser();
            
            if($user === null) return new RedirectRenderer('login');
            $userId = $user->getId();
            
            $postDAO = DAOFactory::getPostDAO();

            $post = new Post(
                content: $content,
                userId: $userId,
                parentPostId: $parentPostId
            );
            
            $success = $postDAO->create($post);
            if($success === false) throw new Exception('Failed to create reply!');

            return new RedirectRenderer('post?id=' . $parentPostId);
        } catch (Exception $e) {
            error_log($e->getMessage());

            return new RedirectRenderer('login');
        }
    })->setMiddleware(['auth']),
    'form/like' => Route::create('form/like', function(): HTTPRenderer {
        try {
            if($_SERVER['REQUEST_METHOD'] !== 'POST') throw new Exception('Invalid request method!');

            $postId = $_POST['post_id'];

            // TODO: do validation

            $user = Authenticate::getAuthenticatedUser();
            if($user === null) return new RedirectRenderer('login');

            $userId = $user->getId();

            $likeDAO = DAOFactory::getLikeDAO();
            $like = new Like(
                userId: $userId,
                postId: $postId
            );
            
            $isLiked = $likeDAO->checkIsLiked($like);
            $success = $isLiked ? $likeDAO->unlike($like) : $likeDAO->createLike($like);
            
            if($success === false) throw new Exception('Failed to like post!');
            
            return new JSONRenderer(['status' => 'success']);
        } catch (Exception $e) {
            error_log($e->getMessage());

            return new RedirectRenderer('login');
        }
    })->setMiddleware(['auth']),
];

