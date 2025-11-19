<?php

namespace App\Model;

class Todo{
    public int $id;
    public string $title;
    public bool $done = false;
    // ?の意味
    // null を許可する NULL許容型 (Nullable Type)
    public ?string $completedAt = null;

    // __construct
    // PHPの予約済みの特別なメソッド
    // クラスから新しいオブジェクト（インスタンス）が作成された直後に、自動的に実行されるメソッド
    public function __construct(int $id, string $title, bool $done = false, ?string $completedAt = null){
        $this->id = $id;
        $this->title = $title;
        $this->done = $done;
        $this->completedAt = $completedAt;
    }


}