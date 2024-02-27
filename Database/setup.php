<?php

use Database\MySQLWrapper;

$mysqli = new MySQLWrapper();

// /Database/Examples/以下のsqlファイルを読み込んで実行する
$path = __DIR__ . '/Examples/';
$files = array_diff(scandir($path), ['.', '..']);
foreach ($files as $file) {
  $result = $mysqli->query(file_get_contents($path . $file));
  if ($result === false) throw new Exception('Could not execute query.');
  else print("Successfully ran all SQL setup queries." . PHP_EOL);
}
