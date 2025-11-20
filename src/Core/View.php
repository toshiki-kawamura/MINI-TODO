<?php

namespace App\Core;

// viewファイルの設定
class View{
    // staticキーワード
    // メソッドやプロパティが「インスタンス」ではなく「クラス自体」に属することを意味する
    public static function render(string $view, array $data = []): void{
        $viewFile = __DIR__ . '/../../views/' . $view . '.php';

        // file_exists(string $filename): bool
        // 指定されたパスにファイルまたはディレクトリが存在するかどうかを確認する関数
        if (!file_exists($viewFile)){
            // throw
            // エラーを例外として投げる
            throw new \Exception("View not found: $viewFile");
        }

        // extract(array $array, int $flags = EXTR_OVERWRITE, string $prefix = ""): int
        // 配列のキー（連想配列の場合）を、そのまま変数名として現在のスコープにインポート（展開）する関数
        extract($data, EXTR_SKIP); //変数を展開する（危険だがフレームワークでは一般的）

        // include:ファイルを取り込む
        include $viewFile;
    }
}