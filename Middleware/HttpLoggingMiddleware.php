<?php

namespace Middleware;

use Response\HTTPRenderer;
use DateTime;
use Helpers\Settings;

class HttpLoggingMiddleware implements Middleware
{
  public function handle(callable $next): HTTPRenderer
  {
    // ログドライバーによってログを残す場所を変更する
    $driver = Settings::env('LOG_DRIVER');

    return match ($driver) {
      'stdout' => $this->stdOutLog($next),
      default => $this->fileLog($next),
    };
  }


  private function fileLog(callable $next): HTTPRenderer
  {
    // 前処理　リクエストログ
    $start = microtime(true);

    ob_start();
    echo "Logging the request\n";
    $dateTime = (new DateTime())->format('Y-m-d H:i:s') . " ";
    echo $dateTime;

    $url = "http" . (isset($_SERVER['HTTPS']) ? "s" : "") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI] ";
    echo $url;

    $requestMethod = "Method:" . $_SERVER['REQUEST_METHOD'] . "\n";
    echo $requestMethod;

    $queryParameters = $_GET;
    echo "Param:";
    print_r($queryParameters);

    $headers = getallheaders();
    echo "Header:";
    print_r($headers);

    $logContent = ob_get_clean();

    file_put_contents("../Storage/Logs/request_log.txt", $logContent, FILE_APPEND);

    $response =  $next();

    // 後処理　レスポンスログ
    ob_start();
    echo "Logging the response\n";
    $statusCode = http_response_code();
    echo "Status:" . $statusCode . " ";

    $end = microtime(true);
    $responseTime = $end - $start;
    echo "Response Time:" . $responseTime . "\n";

    $headers = headers_list();
    echo "Header:";
    print_r($headers);

    $logContent = ob_get_clean();
    file_put_contents("../Storage/Logs/request_log.txt", $logContent, FILE_APPEND);

    return $response;
  }

  public function stdOutLog(callable $next): HTTPRenderer
  {
    // 前処理　リクエストログ
    $start = microtime(true);

    $logMessage = "Logging the request " .
      (new DateTime())->format('Y-m-d H:i:s') . " " .
      "http" . (isset($_SERVER['HTTPS']) ? "s" : "") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]  " .
      "Method:" . $_SERVER['REQUEST_METHOD'] . " " .
      "Param:" . print_r($_GET, true) . " " .
      "Header:" . print_r(getallheaders(), true);

    error_log($logMessage);

    $response = $next();

    // 後処理　レスポンスログ
    $logMessage = "Logging the response " .
      "Status:" . http_response_code() . " " .
      "Response Time:" . (microtime(true) - $start) . " " .
      "Header:" . print_r(headers_list(), true);

    error_log($logMessage);

    return $response;
  }
}
