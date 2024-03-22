<?php

use Exceptions\AuthenticationFailureException;
use Helpers\ValidationHelper;
use Helpers\Authenticate;
use Helpers\FileHelper;
use Helpers\EncryptionHelper;
use Response\FlashData;
use Response\HTTPRenderer;
use Response\Render\HTMLRenderer;
use Response\Render\RedirectRenderer;
use Database\DataAccess\DAOFactory;
use Helpers\Settings;
use Response\Render\JSONRenderer;
use Routing\Route;
use Types\ValueType;
use Models\Profile;
use Models\User;
use Models\Follow;
use Models\Message;
use Models\Post;
use Models\PostLike;
use Models\Reply;
use Models\Conversation;

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
    $postLikeDAO = DAOFactory::getPostLikeDAO();

    $replyDAO = DAOFactory::getReplyDAO();

    $login_user_id = $_SESSION['user_id'];

    foreach ($data as $data) {
      $data_list[] = [
        'post' => $data['post'],
        "profile" => $data["profile"],
        'reply' => $replyDAO->getReplyByPostId($data['post']->getId()),
        'postLikeCount' => $postLikeDAO->getLikeCountByPostId($data['post']->getId()),
        'isLike' => $postLikeDAO->getLikeByUserId($login_user_id, $data['post']->getId()),
      ];
    }

    $login_user_profile = DAOFactory::getProfileDAO()->getById($login_user_id);
    $login_user_profile_image_path = $login_user_profile->getProfileImage();

    return new HTMLRenderer('page/home', ['data_list' => $data_list, 'login_user_profile_image_path' => $login_user_profile_image_path]);
  })->setMiddleware(['auth']),

  'form/post' => Route::create(
    'form/post',
    function (): HTTPRenderer {
      try {
        // TODO: 入力された内容を検証する
        $request_fields = [
          'content' => ValueType::CONTENT,
        ];

        $validatedData = ValidationHelper::validateFields($request_fields, $_POST);

        $postDAO = DAOFactory::getPostDAO();

        $post_file_path = null;

        if (FileHelper::isExitUserUploadFile($_FILES)) {
          // 存在していればハッシュ化されたimage_pathを取得
          $post_file_path = FileHelper::getFilePath($_FILES);
        }
        $file_type = $_FILES['image']['type'];
        error_log($file_type);

        $post = new Post(
          content: $validatedData['content'],
          user_id: $_SESSION['user_id'],
          image_path: $file_type === "video/mp4" ? null : $post_file_path,
          video_path: $file_type === "video/mp4" ? $post_file_path : null,
        );

        //TODO: 画像を保存するロジックの追加をする
        $postDAO->create($post);

        if (!is_null($post_file_path)) FileHelper::saveImageFile($post_file_path);

        FlashData::setFlashData('success', '投稿が完了しました!');

        return new JSONRenderer(['status' => 'success']);
      } catch (Exception $e) {
        error_log($e->getMessage());

        FlashData::setFlashData('error', '投稿に失敗しました.');
        return new JSONRenderer(["status" => "error."]);
      } catch (\InvalidArgumentException $e) {
        error_log($e->getMessage());

        FlashData::setFlashData('error', $e->getMessage());
        return new JSONRenderer(["status" => "error."]);
      }
    }
  )->setMiddleware(['auth']),

  'form/post/delete' => Route::create('form/post/delete', function (): HTTPRenderer {
    try {
      $data = $_POST;

      // 削除対象がログインしているユーザーのものかを検証
      $login_user_id = $_SESSION['user_id'];
      $post_user_id = intval($data['post_user_id']);

      ValidationHelper::isUserPost($login_user_id, $post_user_id);

      $postDAO = DAOFactory::getPostDAO();

      $target_post_id = intval($data['post_id']);

      $is_post_deleted = $postDAO->delete($target_post_id);

      if (!$is_post_deleted) throw new Exception("");

      FlashData::setFlashData('success', '投稿の削除に成功しました');
      return new JSONRenderer(['status' => 'success']);
    } catch (\InvalidArgumentException $e) {

      error_log($e->getMessage());
      return new JSONRenderer(['status' => 'error']);
    } catch (Exception $e) {

      error_log($e->getMessage());
      return new JSONRenderer(['status' => 'error']);
    }
  })->setMiddleware(['auth']),

  'edit/profile' => Route::create('profile', function (): HTTPRenderer {

    $user_id = $_SESSION['user_id'];

    $profileDAO = DAOFactory::getProfileDAO();

    $profile = $profileDAO->getById($user_id);

    return new HTMLRenderer('page/editProfile', ['profile' => $profile]);
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
      $profile_image_path = null;

      if (FileHelper::isExitUserUploadFile($_FILES)) {
        $profile_image_path = FileHelper::getFilePath($_FILES);
      }
      $profileDAO = DAOFactory::getProfileDAO();

      $profileDAO->updateProfileImage($profile_image_path);


      // private/uploads/images/に保存
      // $hashed_file_name = FileHelper::saveImageFile($file_name);

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

  'form/unlike' => Route::create('form/unlike', function (): HTTPRenderer {
    try {
      $post__id = $_POST['post_id'];
      $login_user_id = $_SESSION['user_id'];


      $postLikeDAO = DAOFactory::getPostLikeDAO();

      $postLike = new PostLike(
        user_id: $login_user_id,
        post_id: $post__id,
      );


      $postLikeDAO->removePostLike($postLike);
      return new JSONRenderer(['status' => 'success']);
    } catch (\Exception $e) {

      error_log($e->getMessage());

      return new JSONRenderer(['status' => 'error']);
    }
  })->setMiddleware(['auth']),

  'form/like' => Route::create('form/like', function (): HTTPRenderer {

    try {
      $post__id = $_POST['post_id'];
      $login_user_id = $_SESSION['user_id'];

      $postLikeDAO = DAOFactory::getPostLikeDAO();

      $postLike = new PostLike(
        user_id: $login_user_id,
        post_id: $post__id,
      );


      $postLikeDAO->addPostLike($postLike);
      return new JSONRenderer(['status' => 'success']);
    } catch (\Exception $e) {

      error_log($e->getMessage());

      return new JSONRenderer(['status' => 'error']);
    }
  })->setMiddleware(['auth']),

  'form/reply' => Route::create('form/reply', function (): HTTPRenderer {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') throw new Exception('Invalid request method!');

    try {
      $request_field = [
        "reply_content" => ValueType::STRING,
      ];

      $validationData = ValidationHelper::validateFields($request_field, $_POST);

      $replyDAO = DAOFactory::getReplyDAO();

      $reply = new Reply(
        content: $validationData['reply_content'],
        user_id: $_SESSION['user_id'],
        post_id: $_POST['post_id'],
      );

      $replyDAO->createReply($reply);

      return new JSONRenderer(['status' => 'success']);
    } catch (\InvalidArgumentException $e) {
      error_log($e->getMessage());
    } catch (\Exception $e) {
      error_log($e->getMessage());
    }

    // TODO: Validation
  })->setMiddleware(['auth']),

  'form/reply/delete' => Route::create('form/reply/delete', function (): HTTPRenderer {

    try {
      $reply_id = $_POST['reply_id'];

      $replyDAO = DAOFactory::getReplyDAO();

      $replyDAO->deleteReply($reply_id);

      return new JSONRenderer(['status' => 'success']);
    } catch (\Exception $e) {
      error_log($e->getMessage());
    }
  })->setMiddleware(['auth']),

  'message' => Route::create('message', function (): HTTPRenderer {
    $url = $_SERVER['PATH_INFO'];
    preg_match('/\/message\/(.+)/', $url, $matches);

    $message_id = count($matches) === 0 ? null : $matches[1];

    if (!is_null($message_id)) {

      $conversationDAO = DAOFactory::getConversation();

      $conversation = $conversationDAO->getConversationById($message_id);


      $messageDAO = DAOFactory::getMessage();

      $messages = $messageDAO->getAllMessageById($conversation->getConversationId());

      $profileDAO = DAOFactory::getProfileDAO();

      $another_user_id = $_SESSION['user_id'] === $conversation->getParticipate1Id() ? $conversation->getParticipate2Id() : $conversation->getParticipate1Id();

      $another_user_profile = $profileDAO->getById($another_user_id);

      $login_user_id = $_SESSION['user_id'];
      $login_user_profile = DAOFactory::getProfileDAO()->getById($login_user_id);

      return new HTMLRenderer('page/message-detail', ['conversation' => $conversation, 'messages' => $messages, 'another_user_profile' => $another_user_profile, 'login_user_profile' => $login_user_profile]);
    }

    $user_id = $_SESSION['user_id'];
    $conversationDAO = DAOFactory::getConversation();

    $data_list = $conversationDAO->getAllConversations($user_id);
    // ログインユーザーのフォローしているユーザーを全件取得する
    $followDAO = DAOFactory::getFollowDAO();

    $followee_users = $followDAO->getAllFollowingUser($user_id);

    return new HTMLRenderer('page/message', ['data_list' => $data_list, 'followee_users' => $followee_users]);
  })->setMiddleware(['auth']),

  'form/message' => Route::create('form/message', function (): HTTPRenderer {

    error_log(print_r($_POST, true));

    $sender_id = $_POST['sender_id'];
    $receiver_id = $_POST['receiver_id'];
    $conversation_id = $_POST['conversation_id'];
    $message_body = $_POST['message_body'];

    $encryptionKey = Settings::env('ENCRYPTION_KEY');

    $encryptionHelper = new EncryptionHelper($encryptionKey);

    // メッセージを暗号化
    $encryptedMessageBody = $encryptionHelper->encrypt($message_body);

    $message = new Message(
      sender_id: $sender_id,
      receiver_id: $receiver_id,
      conversation_id: $conversation_id,
      message_body: $encryptedMessageBody  // 暗号化されたメッセージを使用
    );

    $messageDAO = DAOFactory::getMessage();

    $messageDAO->createMessage($message);

    // TODO:error catch を追加
    return new JSONRenderer(['status' => 'success']);
  })->setMiddleware(['auth']),

  'form/conversation' => Route::create('form/conversation', function (): HTTPRenderer {

    $conversationDAO = DAOFactory::getConversation();

    $conversation = new Conversation(
      participant1_id: $_POST['participant1_id'],
      participant2_id: $_POST['participant2_id']
    );

    $conversationDAO->createConversation($conversation);


    return new JSONRenderer(['status' => "success", 'conversation_id' => $conversation->getConversationId()]);
  })->setMiddleware(['auth']),

  'form/conversation/delete' => Route::create('form/conversation/delete', function (): HTTPRenderer {

    $conversationDAO = DAOFactory::getConversation();

    $conversationDAO->deleteConversation($_POST['conversation_id']);


    // TODO:error catch を追加
    return new JSONRenderer(['status' => "success"]);
  })->setMiddleware(['auth']),

  'notification' => Route::create('notification', function (): HTTPRenderer {

    $notificationDAO = DAOFactory::getNotification();

    $notifications = $notificationDAO->getById($_SESSION['user_id']);
    return new HTMLRenderer('page/notification', ['data_list' => $notifications]);
  })->setMiddleware(['auth']),
];
