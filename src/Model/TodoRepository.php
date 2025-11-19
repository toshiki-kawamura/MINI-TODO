<?php

namespace App\Model;

class TodoRepository{
    private string $file;

    public function __construct(){
        $this->file = __DIR__ . '/../../storage/todos.json';

        if (!file_exists($this->file)){
            file_put_contents($this->file,json_encode([]));
        }
    }
    private function load(): array{
        return json_decode(file_get_contents($this->file), true) ?? [];
    }

    //タスクを保持する
    private function save(array $data): void{
        file_put_contents($this->file, json_encode($data, JSON_PRETTY_PRINT));
    }

    public function all(): array{
        $items = $this->load();

        return array_map(function ($item){
            return new Todo(
                $item['id'],
                $item['title'],
                $item['done'],
                $item['completedAt'] ?? null
            );
        }, $items);
    }

    //タスクを追加する
    public function add(string $title): Todo{
        $items = $this->load();
        $id = count($items) + 1;
        $todo = new Todo($id,$title);

        $items[] = [
            'id' => $todo->id,
            'title' => $todo->title,
            'done'=> $todo->done,
            'completedAt' => $todo->completedAt,
        ];
        $this->save($items);
        return $todo;
    }

    //完了処理
    public function markDone(int $id): void{
        $todos = $this->load();
        foreach ($todos as $t){
            if($t->id == $id){
                $t->done = true;
                $t->completedAt = (new DateTime())->format('Y-m-d H:i:s');
            }
        }
        $this->save($todos);
    }
}