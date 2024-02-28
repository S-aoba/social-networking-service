<?php

use Helpers\ValidationHelper;
use Helpers\Authenticate;
use Response\HTTPRenderer;
use Response\FlashData;
use Response\Render\RedirectRenderer;
use Response\Render\HTMLRenderer;
use Database\DataAccess\DAOFactory;
use Response\Render\JSONRenderer;
use Types\ValueType;
use Models\User;
use Exceptions\AuthenticationFailureException;

return [
  'home' => function (): HTTPRenderer {
    return new HTMLRenderer('page/home');
  },
  'register' => function (): HTTPRenderer {
    if (Authenticate::isLoggedIn()) {
      FlashData::setFlashData('error', 'Cannot register as you are already logged in.');
      return new RedirectRenderer('home');
    }

    return new HTMLRenderer('page/register');
  },
  'form/register' => function (): HTTPRenderer {
    // ユーザが現在ログインしている場合、登録ページにアクセスすることはできません。
    if (Authenticate::isLoggedIn()) {
      FlashData::setFlashData('error', 'Cannot register as you are already logged in.');
      return new RedirectRenderer('home');
    }

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
  },
  'logout' => function (): HTTPRenderer {
    if (!Authenticate::isLoggedIn()) {
      FlashData::setFlashData('error', 'Already logged out.');
      return new RedirectRenderer('home');
    }

    Authenticate::logoutUser();
    FlashData::setFlashData('success', 'Logged out.');
    return new RedirectRenderer('login');
  },
  'login' => function (): HTTPRenderer {
    if (Authenticate::isLoggedIn()) {
      FlashData::setFlashData('error', 'You are already logged in.');
      return new RedirectRenderer('home');
    }

    return new HTMLRenderer('page/login');
  },
  'form/login' => function (): HTTPRenderer {
    if (Authenticate::isLoggedIn()) {
      FlashData::setFlashData('error', 'You are already logged in.');
      return new RedirectRenderer('home');
    }

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
  },
  // Post
  'form/post' => function (): HTTPRenderer {
    if (!Authenticate::isLoggedIn()) {
      FlashData::setFlashData('error', 'Cannot post as you are not logged in.');
      return new RedirectRenderer('home');
    }
    // TODO: 入力された内容を検証する
    // TODO: DBに保村する
    
    return new JSONRenderer(['status' => 'success', 'message' => '投稿が完了しました!']);
  }
];
