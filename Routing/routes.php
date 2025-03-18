<?php

use Database\DataAccess\DAOFactory;
use Helpers\Authenticate;
use Helpers\ValidationHelper;
use Models\Post;
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
        return new HTMLRenderer('page/home');
    })->setMiddleware(['auth']),

    'login' => Route::create('login', function (): HTTPRenderer {
        return new HTMLRenderer('page/login');
    })->setMiddleware(['guest']),
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
    'register' => Route::create('register', function (): HTTPRenderer {
        return new HTMLRenderer('page/register');
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
                username: $validatedData['username'],
                email: $validatedData['email'],
            );

            // データベースにユーザーを作成しようとします
            $success = $userDao->create($user, $validatedData['password']);
            
            if (!$success) throw new Exception('Failed to create new user!');
            
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
    'logout' => Route::create('logout', function (): HTTPRenderer {
        Authenticate::logoutUser();
        FlashData::setFlashData('success', 'Logged out.');
        return new RedirectRenderer('login');
    })->setMiddleware(['auth']),
    'form/post' => Route::create('form/post', function(): HTTPRenderer {
        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') throw new Exception('Invalid request method!');

            $content = $_POST['content'];
            $userId = $_POST['user_id'];
            $parentPostId = $_POST['parent_post_id'] ?? null;

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

            return new JSONRenderer(['status' => 'success']);
        } catch (\Exception $e) {
            error_log($e->getMessage());

            FlashData::setFlashData('error', 'An error occurred.');

            // TODO: Change redirect route to error page or login page.
            return new RedirectRenderer('home');
        }
    }),
];

