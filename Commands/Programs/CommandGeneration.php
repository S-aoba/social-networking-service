<?php

namespace Commands\Programs;

use Commands\AbstractCommand;
use Commands\Argument;

class CommandGeneration extends AbstractCommand
{
  // 使用するコマンド名を設定
  protected static ?string $alias = 'comm-gen';
  protected static bool $requiredCommandValue = true;

  // 引数を割り当て
  public static function getArguments(): array
  {
    return [];
  }

  public function execute(): int
  {
    $commandValue = $this->getCommandValue();
    $commandValue = $this->pascalCase($commandValue);

    $this->log("Generating code for......." . $commandValue);
    //　ボイラーテンプレートを生成
    $boilerplate = $this->generateBoilerplate($commandValue);
    // registry.phpに登録する
    $this->registryClassName($commandValue);

    // ボイラーテンプレートを出力
    echo $boilerplate;

    // ボイラーテンプレートをファイルに保存
    $fileName = sprintf('Commands/Programs/%s.php', $commandValue);
    file_put_contents($fileName, $boilerplate);

    return 0;
  }

  private function generateBoilerplate(string $commandValue): string
  {
    $className = $commandValue;
    $alias = '$alias';

    return
      <<<BOILERPLATE
          <?php

          namespace Commands\Programs;

          use Commands\AbstractCommand;
          use Commands\Argument;

          class {$className} extends AbstractCommand
          {
              // TODO: エイリアスを設定してください。
              protected static ?string {$alias} = '{INSERT COMMAND HERE}';

              // TODO: 引数を設定してください。
              public static function getArguments(): array
              {
                  return [];
              }

              // TODO: 実行コードを記述してください。
              public function execute(): int
              {
                  return 0;
              }
          }
          BOILERPLATE;
  }

  private function pascalCase(string $string): string
  {
    return str_replace(' ', '', ucwords(str_replace('_', ' ', $string)));
  }

  private function registryClassName(string $className): void
  {
    $registryDir = 'Commands/registry.php';
    $commands = file_get_contents($registryDir);

    $newCommands = sprintf('  ' . 'Commands\Programs\%s::class' . ',' . PHP_EOL, $className);
    $add_index = strrpos($commands, '];');

    $commands = substr_replace($commands, $newCommands, $add_index, 0);
    file_put_contents($registryDir, $commands);
  }
}
