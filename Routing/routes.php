<?php

use Exceptions\AuthenticationFailureException;
use Helpers\ValidationHelper;
use Helpers\Authenticate;
use Response\FlashData;
use Response\HTTPRenderer;
use Response\Render\HTMLRenderer;
use Response\Render\RedirectRenderer;
use Database\DataAccess\DAOFactory;
use Response\Render\JSONRenderer;
use Routing\Route;
use Types\ValueType;
use Models\User;

return [
  'login' => Route::create('login', function (): HTTPRenderer {
    return new HTMLRenderer('page/login');
  })->setMiddleware(['guest']),
  'form/login' => Route::create('form/login', function (): HTTPRenderer {
    try {
      if ($_SERVER['REQUEST_METHOD'] !== 'POST') throw new Exception('Invalid request method!');

      $required_fields = [
        'email' => ValueType::EMAIL,
        'password' => ValueType::PASSWORD,
      ];

      $validatedData = ValidationHelper::validateFields($required_fields, $_POST);

      Authenticate::authenticate($validatedData['email'], $validatedData['password']);

      FlashData::setFlashData('success', 'Logged in successfully.');
      return new RedirectRenderer('home');
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

      if ($validatedData['confirm_password'] !== $validatedData['password']) {
        FlashData::setFlashData('error', 'Invalid Password!');
        return new RedirectRenderer('register');
      }

      // Eメールは一意でなければならないので、Eメールがすでに使用されていないか確認します
      if ($userDao->getByEmail($validatedData['email'])) {
        FlashData::setFlashData('error', 'Email is already in use!');
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

      // ユーザーログイン
      Authenticate::loginAsUser($user);

      FlashData::setFlashData('success', 'Account successfully created.');
      return new RedirectRenderer('home');
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

  'home' => Route::create('home', function (): HTTPRenderer {
    // DBから投稿を取得
    $postDAO = DAOFactory::getPostDAO();

    $posts = $postDAO->getAllPosts(0, 10);

    $post_list = [];

    foreach ($posts as $post) {
      $post_list[] = [
        "id" => $post->getId(),
        "content" => $post->getContent(),
        "timeStamp" => $post->getTimeStamp(),
        "user_id" => $post->getUserId()
      ];
    }
    return new HTMLRenderer('page/home', ['post_list' => $post_list]);
  })->setMiddleware(['auth']),

  // Post
  'form/post' => Route::create(
    'form/post',
    function (): HTTPRenderer {
      if (!Authenticate::isLoggedIn()) {

        FlashData::setFlashData('error', 'Cannot post as you are not logged in.');

        return new RedirectRenderer('home');
      }

      // TODO: 入力された内容を検証する
      // TODO: DBに保存する
      $postDAO = DAOFactory::getPostDAO();

      $postDAO->create($_POST['content'], $_SESSION['user_id']);

      return new JSONRenderer(['status' => 'success', 'message' => '投稿が完了しました!']);
    }
  )->setMiddleware(['auth'])
];
