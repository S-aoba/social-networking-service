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
  // Page
  'home' => Route::create('home', function (): HTTPRenderer {
    try {
      $data_list = [];

      $postDAO = DAOFactory::getPostDAO();

      $postLikeDAO = DAOFactory::getPostLikeDAO();

      $replyDAO = DAOFactory::getReplyDAO();

      $presentationTab = isset($_COOKIE['presentation-tab']) ? $_COOKIE['presentation-tab'] : 'trend';
      if (isset($_SESSION['user_id'])) {

        if ($presentationTab === 'trend') $data = $postDAO->getTrendingPosts(0, 10);

        if ($presentationTab === 'follower') $data = $postDAO->getFollowerPost(0, 10);

        $login_user_id = $_SESSION['user_id'];

        foreach ($data as $data) {
          $data_list[] = [
            'post' => $data['post'],
            'profile' => $data['profile'],
            'reply' => $replyDAO->getReplyByPostId($data['post']->getId()),
            'postLikeCount' => $postLikeDAO->getLikeCountByPostId($data['post']->getId()),
            'isLike' => $postLikeDAO->getLikeByUserId($login_user_id, $data['post']->getId()),
            'replyCount' => $replyDAO->getReplyCountForPost($data['post']->getId())
          ];
        }

        $login_user_profile = DAOFactory::getProfileDAO()->getById($login_user_id);
        $login_user_profile_image_path = $login_user_profile->getUploadFullPathOfProfileImage();
      } else {
        $data = $postDAO->getPublicPosts(0, 10);
        foreach ($data as $data) {
          $data_list[] = [
            'post' => $data['post'],
            "profile" => $data["profile"],
            'reply' => $replyDAO->getReplyByPostId($data['post']->getId()),
            'postLikeCount' => $postLikeDAO->getLikeCountByPostId($data['post']->getId()),
            'isLike' => null,
            'replyCount' => $replyDAO->getReplyCountForPost($data['post']->getId())
          ];
        }
        $login_user_profile_image_path = null;
      }

      return new HTMLRenderer('page/home', ['data_list' => $data_list, 'login_user_profile_image_path' => $login_user_profile_image_path, 'presentationTab' => $presentationTab]);
    } catch (\Throwable $th) {
      //throw $th;
    }
  }),

  'profile' => Route::create('profile', function (): HTTPRenderer {

    // 表示対象のユーザー情報
    $url = $_SERVER['PATH_INFO'];
    preg_match('/\/profile\/(.+)/', $url, $matches);
    $user_id = intval($matches[1]);

    $profileDAO = DAOFactory::getProfileDAO();
    $profile = $profileDAO->getByUsername($user_id);

    // フォロー数とフォロワー数
    $followDAO = DAOFactory::getFollowDAO();

    $follow = new Follow(
      follow_id: $_SESSION['user_id'],
      followee_id: $profile->getUserId(),
    );

    $is_follow = $followDAO->checkFollow($follow);

    $follow_count = $followDAO->getFollowUserCount($follow);
    $follower_count = $followDAO->getFollowerUserCount($follow);

    // 表示対象のユーザーの投稿一覧
    $postDAO = DAOFactory::getPostDAO();

    $posts = $postDAO->getAllPostByUserId($user_id);

    $data_list = [];
    $postLikeDAO = DAOFactory::getPostLikeDAO();

    $replyDAO = DAOFactory::getReplyDAO();

    foreach ($posts as $data) {
      $data_list[] = [
        'post' => $data['post'],
        "profile" => $data["profile"],
        'reply' => $replyDAO->getReplyByPostId($data['post']->getId()),
        'postLikeCount' => $postLikeDAO->getLikeCountByPostId($data['post']->getId()),
        'isLike' => $postLikeDAO->getLikeByUserId($_SESSION['user_id'], $data['post']->getId()),
        'replyCount' => $replyDAO->getReplyCountForPost($data['post']->getId())
      ];
    }

    $is_self_profile = $user_id === $_SESSION['user_id'] ? true : false;

    // 表示対象ユーザーのポスト数
    $post_count = $postDAO->getPostCountByUserId($user_id);

    return new HTMLRenderer('page/profile', [
      'profile' => $profile, "is_follow" => $is_follow, 'is_self_profile' => $is_self_profile, 'data_list' => $data_list, 'follow_count' => $follow_count[0],
      'follower_count' => $follower_count[0], 'post_count' => $post_count
    ]);
  })->setMiddleware(['auth']),

  'message' => Route::create('message', function (): HTTPRenderer {
    $url = $_SERVER['PATH_INFO'];
    preg_match('/\/message\/(.+)/', $url, $matches);

    $message_id = count($matches) === 0 ? null : $matches[1];

    $user_id = $_SESSION['user_id'];
    $conversationDAO = DAOFactory::getConversation();

    // ログインユーザーのフォローしているユーザーを全件取得する
    $followDAO = DAOFactory::getFollowDAO();

    $followee_users = $followDAO->getAllFollowingUser($user_id);

    $data_list = $conversationDAO->getAllConversations($user_id);

    if (!is_null($message_id)) {

      $conversation = $conversationDAO->getConversationById($message_id);

      if (is_null($conversation)) {
        return new RedirectRenderer('message');
      }

      $messageDAO = DAOFactory::getMessage();

      $messages = $messageDAO->getAllMessageById($conversation->getConversationId());

      $profileDAO = DAOFactory::getProfileDAO();

      $another_user_id = $_SESSION['user_id'] === $conversation->getParticipate1Id() ? $conversation->getParticipate2Id() : $conversation->getParticipate1Id();

      $another_user_profile = $profileDAO->getById($another_user_id);

      $login_user_id = $_SESSION['user_id'];
      $login_user_profile = DAOFactory::getProfileDAO()->getById($login_user_id);

      return new HTMLRenderer('page/message-detail', ['data_list' => $data_list, 'conversation' => $conversation, 'messages' => $messages, 'another_user_profile' => $another_user_profile, 'login_user_profile' => $login_user_profile, 'followee_users' => $followee_users]);
    }

    return new HTMLRenderer('page/message', ['data_list' => $data_list, 'followee_users' => $followee_users]);
  })->setMiddleware(['auth']),

  'notification' => Route::create('notification', function (): HTTPRenderer {

    $notificationDAO = DAOFactory::getNotification();

    $notifications = $notificationDAO->getById($_SESSION['user_id']);


    $notificationDAO->toggleReadStatus($_SESSION['user_id']);

    return new HTMLRenderer('page/notification', ['data_list' => $notifications]);
  })->setMiddleware(['auth']),

  'status' => Route::create('status', function (): HTTPRenderer {

    // URL: {domain}/{userId}/status/{postId}
    try {
      $url = $_SERVER['PATH_INFO'];
      preg_match('#^/([a-zA-Z0-9_]+)/status/([a-zA-Z0-9_]+)$#', $url, $matches);

      $post_id = intval($matches[2]);

      $postDAO = DAOFactory::getPostDAO();
      $data = $postDAO->getByPostId($post_id);

      if (is_null($data)) return new HTMLRenderer('page/post-detail', ['data' => []]);

      $postLikeDAO = DAOFactory::getPostLikeDAO();

      $replyDAO = DAOFactory::getReplyDAO();

      $data_list[] = [
        'post' => $data['post'],
        "profile" => $data["profile"],
        'postLikeCount' => $postLikeDAO->getLikeCountByPostId($data['post']->getId()),
        'isLike' => $postLikeDAO->getLikeByUserId($_SESSION['user_id'], $data['post']->getId()),
        'replyCount' => $replyDAO->getReplyCountForPost($data['post']->getId())
      ];

      $replies = $replyDAO->getReplyByPostId($data['post']->getId());

      return new HTMLRenderer('page/post-detail', ['data' => $data_list[0], 'replies' => $replies]);
    } catch (\Exception $e) {
      error_log($e->getMessage());
      return new HTMLRenderer('page/home', ['status' => 'error']);
    }
  }),

  // Forms
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
        return new RedirectRenderer('home');
      }

      // Eメールは一意でなければならないので、Eメールがすでに使用されていないか確認します
      if ($userDao->getByEmail($validatedData['email'])) {
        FlashData::setFlashData('error', 'Email is already in use!');
        return new RedirectRenderer('home');
      }

      // 新しいUserオブジェクトを作成します
      $user = new User(
        email: $validatedData['email'],
      );

      $username = $_POST['username'];

      // データベースにユーザーを作成しようとします
      $success = $userDao->create($user, $validatedData['password'], $username);

      if (!$success) throw new Exception('Failed to create new user!');

      // ユーザーログイン
      Authenticate::loginAsUser($user);

      FlashData::setFlashData('success', 'Account successfully created.');
      return new RedirectRenderer('home');
    } catch (\InvalidArgumentException $e) {
      error_log($e->getMessage());

      FlashData::setFlashData('error', 'Invalid Data.');
      return new RedirectRenderer('home');
    } catch (Exception $e) {
      error_log($e->getMessage());

      FlashData::setFlashData('error', 'An error occurred.');
      return new RedirectRenderer('home');
    }
  })->setMiddleware(['guest']),

  'form/post' => Route::create(
    'form/post',
    function (): HTTPRenderer {
      try {
        // TODO: 入力された内容を検証する
        $request_fields = [
          'content' => ValueType::CONTENT,
        ];

        $validated_data = ValidationHelper::validateFields($request_fields, $_POST);
        $validated_files = ValidationHelper::validateFiles($_FILES['image']);

        $postDAO = DAOFactory::getPostDAO();

        // Postをインスタンスかするためにfile_pathが存在していれば、ハッシュ化したfile_pathを生成する
        $hashed_file_path = is_null($validated_files) ? null : FileHelper::getHashedFilePath($validated_files['file']);
        $file_type = is_null($validated_files) ? null : $validated_files['type'];

        // Postクラスをインスタンス化
        $post = new Post(
          content: $validated_data['content'],
          user_id: $_SESSION['user_id'],
          file_path: $hashed_file_path,
          file_type: $file_type
        );

        // PostをDBへ作成する
        $postDAO->create($post);

        // Fileが投稿されているかつまだprivate/uploads配下に保存されていない場合のみ
        // すでに保存されていれば、以下のコードはスキップしたほうが効率がいい
        if (!is_null($validated_files) && !FileHelper::isExitUploadFilePath($hashed_file_path, $validated_files['type'])) {
          // Fileをpublic/private/uploads/配下に配置する
          FileHelper::saveFilePathInUploadsDir($validated_files['file']['tmp_name'], $hashed_file_path, $validated_files['type']);
        }

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
      error_log($login_user_id);
      error_log($post_user_id);
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

  'form/update/profile' => Route::create('form/update/profile', function (): HTTPRenderer {
    try {

      $request_fields = [
        // TODO:ValidationHelper::validateFieldsに新たにusername,selfIntroduction用の値を作成する
        'username' => ValueType::STRING,
        // 'self-introduction' => ValueType::STRING
      ];

      $validated_data = ValidationHelper::validateFields($request_fields, $_POST);
      $validated_header_image = ValidationHelper::validateFiles($_FILES['header-image']);
      $validated_profile_image = ValidationHelper::validateFiles($_FILES['profile-image']);

      $hashed_profile_image_path = is_null($validated_profile_image) ? null : FileHelper::getHashedFilePath($validated_profile_image['file']);
      $hashed_header_image_path = is_null($validated_header_image) ? null : FileHelper::getHashedFilePath($validated_header_image['file']);


      $profileDAO = DAOFactory::getProfileDAO();

      $profile = new Profile(
        user_id: $_SESSION['user_id'],
        username: $validated_data['username'],
        self_introduction: $_POST['self-introduction'],
        profile_image_path: $hashed_profile_image_path,
        header_path: $hashed_header_image_path
      );

      $profileDAO->updateProfile($profile);

      if (!is_null($validated_header_image) && !FileHelper::isExitUploadFilePath($hashed_header_image_path, $validated_header_image['type'])) {
        FileHelper::saveFilePathInUploadsDir($validated_header_image['file']['tmp_name'], $hashed_header_image_path, $validated_header_image['type']);
      } elseif (!is_null($validated_profile_image) && !FileHelper::isExitUploadFilePath($hashed_profile_image_path, $validated_profile_image['type'])) {
        FileHelper::saveFilePathInUploadsDir($validated_profile_image['file']['tmp_name'], $hashed_profile_image_path, $validated_profile_image['type']);
      }

      return new JSONRenderer(["status" => "success"]);
    } catch (Exception $e) {
      return new JSONRenderer(["status" => 'error', 'message' => "画像の保存中に問題が発生しました。申し訳ありませんが、後でもう一度お試しください。"]);
    } catch (\InvalidArgumentException $e) {
      error_log($e->getMessage());
      return new JSONRenderer(["status" => 'error', 'message' => "画像の保存中に問題が発生しました。申し訳ありませんが、後でもう一度お試しください。"]);
    }
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
      $post_user_id = $_POST['post_user_id'];


      $postLikeDAO = DAOFactory::getPostLikeDAO();

      $postLike = new PostLike(
        user_id: $login_user_id,
        post_id: $post__id,
        post_user_id: $post_user_id
      );


      $postLikeDAO->removePostLike($postLike);
      return new JSONRenderer(['status' => 'success']);
    } catch (\Exception $e) {

      error_log($e->getMessage());

      return new JSONRenderer(['status' => 'error']);
    }
  })->setMiddleware(['auth']),

  'form/like' => Route::create('form/like', function (): HTTPRenderer {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') throw new Exception('Invalid request method!');
    try {
      $post__id = $_POST['post_id'];
      $login_user_id = $_SESSION['user_id'];
      $post_user_id = $_POST['post_user_id'];
      $post_content = $_POST['post_content'];

      $postLikeDAO = DAOFactory::getPostLikeDAO();

      $postLike = new PostLike(
        user_id: $login_user_id,
        post_id: $post__id,
        post_user_id: $post_user_id
      );


      $postLikeDAO->addPostLike($postLike, $post_content);
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
        "reply_content" => ValueType::ReplyContent,
      ];

      $validationData = ValidationHelper::validateFields($request_field, $_POST);
      $validated_files = ValidationHelper::validateFiles($_FILES['image']);

      $hashed_file_path = is_null($validated_files) ? null : FileHelper::getHashedFilePath($validated_files['file']);
      $file_type = is_null($validated_files) ? null : $validated_files['type'];

      $replyDAO = DAOFactory::getReplyDAO();

      $reply = new Reply(
        content: $validationData['reply_content'],
        user_id: $_SESSION['user_id'],
        post_id: $_POST['post_id'],
        file_path: $hashed_file_path,
        file_type: $file_type
      );

      $replyDAO->createReply($reply);
      // replyのreplyの場合の処理

      return new JSONRenderer(['status' => 'success']);
    } catch (\InvalidArgumentException $e) {
      error_log($e->getMessage());

      return new JSONRenderer(['status' => 'error']);
    } catch (\Exception $e) {
      error_log($e->getMessage());

      return new JSONRenderer(['status' => 'error']);
    }
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

  'form/message' => Route::create('form/message', function (): HTTPRenderer {

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

  'logout' => Route::create('logout', function (): HTTPRenderer {

    Authenticate::logoutUser();

    FlashData::setFlashData('success', 'Logged out.');

    return new RedirectRenderer('home');
  })->setMiddleware(['auth']),
];
