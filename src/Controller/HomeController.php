<?php

namespace App\Controller;

use App\Core\View;
use App\Model\TodoRepository;

class HomeController{
    public function index(array $params = []): void{
        $repo = new TodoRepository();
        $todos = $repo->all();
        View::render('home',[
            'todos' => $todos
        ]);
    }

    public function add(array $params = []): void{
        $title = trim($_POST['title'] ?? '');

        if ($title === ''){
            header('Location: /');
            exit;
        }
        $repo = new TodoRepository();
        $repo->add($title);

        //リダイレクト
        header("Location: /");
        exit;
    }

    // 完了にする
    public function done(array $params): void{
        $id = isset($params['id']) ? (int)$params['id'] : 0;
        if ($id <= 0){

            header("Location: /");
            exit;
        }
        $repo = new TodoRepository();
        $repo->markDone($id);

        header("Location: /");
        exit;
    }

    //未完了にする
    public function undo(array $params): void {
        $id = isset($params['id']) ? (int)$params['id'] : 0;
        if ($id <= 0){
            header('Location: /');
            exit;
        }
        $repo = new TodoRepository();

        $repo->undoDone($id);
        header("Location: /");
        exit;
    }
}

// Laravelでいう
// class HomeController extends Controller
// に相当する最小単位
