<?php
namespace App\Core;

class Controller {
    
    protected function model($model) {
        $modelClass = "\\App\\Models\\" . $model;
        return new $modelClass();
    }

    protected function view($view, $data = []) {
        extract($data);
        
        require_once "../views/layout/header.php";
        require_once "../views/" . $view . ".php";
        require_once "../views/layout/footer.php";
    }

    protected function redirect($url) {
        header("Location: $url");
        exit;
    }

    protected function json($data) {
        header('Content-Type: application/json');
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        exit;
    }
}
?>