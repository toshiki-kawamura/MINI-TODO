<?php

namespace App\Model;

class TodoRepository{
    private string $file;

    public function __construct(){
        $this->file = __DIR__ . '/../../storage/todos.json';

        if (!file_exists($this->file)){
            file_put_contents($this->file, json_encode([], JSON_PRETTY_PRINT));
        }
    }

    //タスクのリストを取得する
    public function all(): array{
        $data = $this->readJsonArray();
        $todos = [];
        foreach ($data as $item){
            //バリデーション；必要なキーがなければスキップ
            if (!is_array($item)) continue;
            if (!isset($item['id']) || !is_int($item['id'])) continue;
            $todos[] = new Todo(
                $item['id'],
                (string)($item['title'] ?? ''),
                (bool)($item['done'] ?? false),
                isset($item['completedAt']) ? $item['completedAt'] : null
            );
        }
        return $todos;
    }

    //タスクを追加する
    public function add(string $title): Todo{
        $items = $this->readJsonArray();

        // 安全な id 採番（既存の最大 +1）
        $ids = array_filter(array_column($items,'id'), 'is_int');
        $nextId = empty($ids) ? 1 : (max($ids) + 1);

        $todo = new Todo($nextId, $title, false, null);

        $items[] = $this->toArray($todo);
        $this->writeJsonArray($items);

        return $todo;
    }

    //完了処理
    public function markDone(int $id): bool{
        $items = $this->readJsonArray();
        $found = false;
        foreach ($items as &$item){
            if(isset($item['id']) && $item['id'] === $id){
                $item['done'] = true;
                // $item['completedAt'] = (new DateTime())->format('Y-m-d H:i:s');
                $item['completedAt'] = date('Y-m-d H:i:s');
                $found = true;
                break;
            }
        }
        if ($found){
            $this->writeJsonArray($items);
        }
        return $found;
    }

    public function undoDone(int $id): bool{
        $items = $this->readJsonArray();
        $found = false;
        foreach ($items as &$item){
            if (isset($item['id']) && $item['id'] === $id){
                $item['done'] = false;
                $item['completedAt'] = null;
                $found = true;
                break;
            }
        }
        if ($found){
            $this->writeJsonArray($items);
        }
        return $found;
    }

    private function readJsonArray(): array{
        $json = file_get_contents($this->file);
        $data = json_decode($json, true);
        if (!is_array($data)){
            return [];
        }
        return $data;
    }

    private function writeJsonArray(array $items): void{
        file_put_contents($this->file, json_encode($items, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }

    private function toArray(Todo $t): array{
        return [
            'id' => $t->id,
            'title' => $t->title,
            'done' => $t->done,
            'completedAt' => $t->completedAt,
        ];
    }
}