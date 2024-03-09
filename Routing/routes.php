<?php

use Exceptions\AuthenticationFailureException;
use Helpers\ValidationHelper;
use Helpers\Authenticate;
use Helpers\FileHelper;
use Response\FlashData;
use Response\HTTPRenderer;
use Response\Render\HTMLRenderer;
use Response\Render\RedirectRenderer;
use Database\DataAccess\DAOFactory;
use Response\Render\JSONRenderer;
use Routing\Route;
use Types\ValueType;
use Models\Profile;
use Models\User;
use Models\Follow;
use Models\Post;
use Models\DataTimeStamp;

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

    $data = $postDAO->getAllPosts(0, 10);

    $data_list = [];

    foreach ($data as $data) {
      $data_list[] = [
        'post' => $data['post'],
        "profile" => $data["profile"],
      ];
    }

    $login_user_profile = DAOFactory::getProfileDAO()->getById($_SESSION['user_id']);
    $login_user_profile_image_path = FileHelper::getProfileImagePath($login_user_profile->getProfileImage());

    return new HTMLRenderer('page/home', ['data_list' => $data_list, 'login_user_profile_image_path' => $login_user_profile_image_path]);
  })->setMiddleware(['auth']),

  'form/post' => Route::create(
    'form/post',
    function (): HTTPRenderer {
      try {
        // TODO: 入力された内容を検証する

        $postDAO = DAOFactory::getPostDAO();

        $postDAO->create($_POST['content'], $_SESSION['user_id']);

        FlashData::setFlashData('success', '投稿が完了しました!');

        return new JSONRenderer(['status' => 'success', 'message' => '投稿が完了しました!']);
      } catch (Exception $e) {
        error_log($e->getMessage());

        FlashData::setFlashData('error', '投稿に失敗しました.');
        return new JSONRenderer(["status" => "error."]);
      }
    }
  )->setMiddleware(['auth']),

  // Userのプロフィールの編集画面
  'edit/profile' => Route::create('profile', function (): HTTPRenderer {

    $user_id = $_SESSION['user_id'];

    $profileDAO = DAOFactory::getProfileDAO();

    $profile = $profileDAO->getById($user_id);

    $profile_image = $profile->getProfileImage();
    $profile_image_path = null;

    if ($profile_image) {
      $profile_image_path = FileHelper::getProfileImagePath($profile_image);
    }

    return new HTMLRenderer('page/editProfile', ['profile' => $profile, "profile_image_path" => $profile_image_path]);
  })->setMiddleware(['auth']),

  'form/update/profile' => Route::create('form/update/profile', function (): HTTPRenderer {
    try {
      $data = $_POST;

      $profileDAO = DAOFactory::getProfileDAO();

      $profile = new Profile(
        user_id: $_SESSION['user_id'],
        username: $data['username'],
        age: intval($data['age']),
        address: $data['address'],
        hobby: $data['hobby'],
        self_introduction: $data['self_introduction'],
        // profile_image_path: $hashed_file_name
      );

      $profileDAO->updateProfile($profile);

      return new JSONRenderer(["status" => "success"]);
    } catch (Exception $e) {
      return new JSONRenderer(["status" => "画像の保存中に問題が発生しました。申し訳ありませんが、後でもう一度お試しください。"]);
    }
  })->setMiddleware(['auth']),
  'form/update/profile-image' => Route::create('form/update/profile-image', function (): HTTPRenderer {

    try {
      $file_name = $_FILES['image']['name'];
      $file_size = $_FILES['image']['size'];
      $file_type = $_FILES['image']['type'];

      // アップロードされた画像のファイルの種類を確認する(対応可能拡張子: jpg, jpeg, png, gif)
      if (!ValidationHelper::checkFileExtension($file_type)) return new JSONRenderer(["status" => "アップロードされたファイルの拡張子が対応していません。"]);
      // アップロードされた画像のファイルサイズを確認する　一回のアップロードの最大サイズ3MBに設定
      if (!FileHelper::checkUploadFileSize($file_size)) return new JSONRenderer(["status" => "アップロードされたファイルのサイズが3MBを超えています。"]);
      // private/uploads/images/に保存
      $hashed_file_name = FileHelper::saveImageFile($file_name);

      $profileDAO = DAOFactory::getProfileDAO();

      $profileDAO->updateProfileImage($hashed_file_name);
      return new JSONRenderer(["status" => "success"]);
    } catch (\Throwable $th) {
      return new JSONRenderer(["status" => "画像の保存中に問題が発生しました。申し訳ありませんが、後でもう一度お試しくください。"]);
    }
  })->setMiddleware(['auth']),

  'profile' => Route::create('profile', function (): HTTPRenderer {
    $url = $_SERVER['PATH_INFO'];
    preg_match('/\/profile\/(.+)/', $url, $matches);
    $username = $matches[1];

    $profileDAO = DAOFactory::getProfileDAO();
    $profile = $profileDAO->getByUsername($username);

    if (!$profile) {
      throw new AuthenticationFailureException();
    }

    $profile_image_path = FileHelper::getProfileImagePath($profile->getProfileImage());

    if ($profile->getUserId() === $_SESSION['user_id']) {
      return new HTMLRenderer('page/selfProfile', ['profile' => $profile, "profile_image_path" => $profile_image_path]);
    }

    $followDAO = DAOFactory::getFollowDAO();

    $follow = new Follow(
      follow_id: $_SESSION['user_id'],
      followee_id: $profile->getUserId(),
    );

    $is_follow = $followDAO->checkFollow($follow);

    return new HTMLRenderer('page/profile', ['profile' => $profile, "profile_image_path" => $profile_image_path, "is_follow" => $is_follow]);
  })->setMiddleware(['auth']),

  'form/follow' => Route::create('form/follow', function (): HTTPRenderer {
    try {
      $data = $_POST;

      $followDAO = DAOFactory::getFollowDAO();

      $follow = new Follow(
        follow_id: $data['userId'],
        followee_id: $data['profileId'],
      );

      $followDAO->addFollow($follow);

      return new JSONRenderer(["status" => "success"]);
    } catch (Exception $e) {
      error_log($e->getMessage());

      FlashData::setFlashData('error', 'An error occurred.');
      return new JSONRenderer(["status" => "error."]);
    }
  })->setMiddleware(['auth']),

  'form/unfollow' => Route::create('form/unfollow', function (): HTTPRenderer {
    try {
      $data = $_POST;

      $followDAO = DAOFactory::getFollowDAO();

      $follow = new Follow(
        follow_id: $data['userId'],
        followee_id: $data['profileId'],
      );

      $followDAO->removeFollow($follow);

      return new JSONRenderer(["status" => "success"]);
    } catch (Exception $e) {
      error_log($e->getMessage());

      FlashData::setFlashData('error', 'An error occurred.');
      return new JSONRenderer(["status" => "error."]);
    }
  })->setMiddleware(['auth']),
];
