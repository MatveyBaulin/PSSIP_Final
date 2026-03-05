<?php

class Controller {
    
    // Загрузка модели
    protected function model($model) {
        require_once "../app/models/" . $model . ".php";
        return new $model();
    }
    
    // Загрузка представления
    protected function view($view, $data = []) {
        extract($data);
        
        require_once "../app/views/layout/header.php";
        require_once "../app/views/" . $view . ".php";
        require_once "../app/views/layout/footer.php";
    }
    
    // Редирект
    protected function redirect($url) {
        header("Location: $url");
        exit;
    }
}