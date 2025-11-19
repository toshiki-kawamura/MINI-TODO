<?php

namespace App\Controller;

use App\Core\View;
use App\Model\TodoRepository;

class HomeController{
    public function index(){
        $repo = new TodoRepository();
        $repo->add("最初のタスク");

        $todos = $repo->all();

        View::render('home',[
            'message' => 'HomeController からのメッセージ',
            'todos' => $todos
        ]);
    }

    public function hello(){
        echo "HomeController::Hello";
    }

    public function task(int $id){
        echo "Task ID = " . $id;
    }

    public function add(){
        if (!isset($_POST['title']) || trim($_POST['title']) === ''){
            echo "title is NONE";
            return;
        }
        $title = trim($_POST['title']);
        $repo = new TodoRepository();
        $repo->add($title);

        //リダイレクト
        header("Location: /");
        exit;
    }
    public function done($params){
        $id = $params['id'];
        $repo = new TodoRepository();
        $repo->markDone($id);

        header("Location: /");
        exit;
    }
}

// Laravelでいう
// class HomeController extends Controller
// に相当する最小単位
