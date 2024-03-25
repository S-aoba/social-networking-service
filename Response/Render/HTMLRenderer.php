<?php

namespace Response\Render;

use Response\HTTPRenderer;
use Helpers\Authenticate;
use Database\DataAccess\DAOFactory;

class HTMLRenderer implements HTTPRenderer
{
  private string $viewFile;
  private array $data;

  public function __construct(string $viewFile, array $data = [])
  {
    $this->viewFile = $viewFile;
    $this->data = $data;
  }

  public function getFields(): array
  {
    return [
      'Content-Type' => 'text/html; charset=UTF-8',
    ];
  }

  public function getContent(): string
  {
    $viewPath = $this->getViewPath($this->viewFile);

    if (!file_exists($viewPath)) {
      throw new \Exception("View file {$viewPath} does not exist.");
    }

    // ユーザー情報を追加
    $this->data['user'] = Authenticate::getAuthenticatedUser();

    // ob_startはすべての出力をバッファに取り込みます。
    // このバッファはob_get_cleanによって取得することができ、バッファの内容を返し、バッファをクリアします。
    ob_start();
    // extract関数は、キーを変数として現在のシンボルテーブルにインポートします
    extract($this->data);
    require $viewPath;
    return $this->getHeader() . ob_get_clean() . $this->getFooter();
  }

  private function getHeader(): string
  {
    ob_start();
    // ユーザーへのアクセスを提供します
    $user = Authenticate::getAuthenticatedUser();

    $is_notification_exists = 0;
    // 通知を取得
    if ($user) {
      $notificationDAO = DAOFactory::getNotification();
      $is_notification_exists = $notificationDAO->checkIsNotificationExists($_SESSION['user_id']);
      $this->data['is_notification_exists'] = $is_notification_exists;
    }
    require $this->getViewPath('layout/header');
    require $this->getViewPath('component/navigator');

    // require $this->getViewPath('component/message-boxes');
    return ob_get_clean();
  }

  private function getFooter(): string
  {
    ob_start();
    require $this->getViewPath('component/information');
    require $this->getViewPath('layout/footer');
    return ob_get_clean();
  }

  private function getViewPath(string $path): string
  {
    return sprintf("%s/%s/Views/%s.php", __DIR__, '../..', $path);
  }
}
