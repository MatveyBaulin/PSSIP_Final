<?php
// app/controllers/BookController.php
require_once "../app/core/Controller.php";

class BookController extends Controller {
    
    private $bookModel;
    
    public function __construct() {
        $this->bookModel = $this->model('Book');
    }
    
    // Главная страница - список книг
    public function index() {
        $books = $this->bookModel->all();
        $this->view('books/index', ['books' => $books]);
    }
    
    // Форма добавления книги
    public function create() {
        $this->view('books/create');
    }
    
    // Сохранение новой книги
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'title' => $_POST['title'] ?? '',
                'author' => $_POST['author'] ?? '',
                'year' => $_POST['year'] ?? null,
                'price' => $_POST['price'] ?? null,
                'description' => $_POST['description'] ?? ''
            ];
            
            // Простая валидация
            if (empty($data['title']) || empty($data['author'])) {
                $_SESSION['error'] = 'Заполните обязательные поля';
                $this->redirect('/book-catalog/public/books/create');
                return;
            }
            
            if ($this->bookModel->create($data)) {
                $_SESSION['success'] = 'Книга успешно добавлена';
                $this->redirect('/book-catalog/public/');
            } else {
                $_SESSION['error'] = 'Ошибка при добавлении книги';
                $this->redirect('/book-catalog/public/books/create');
            }
        }
    }
    
    // Просмотр одной книги
    public function show($id) {
        $book = $this->bookModel->find($id);
        
        if (!$book) {
            $this->redirect('/book-catalog/public/');
            return;
        }
        
        $this->view('books/show', ['book' => $book]);
    }
    
    // Удаление книги
    public function delete($id) {
        if ($this->bookModel->delete($id)) {
            $_SESSION['success'] = 'Книга удалена';
        } else {
            $_SESSION['error'] = 'Ошибка при удалении';
        }
        
        $this->redirect('/book-catalog/public/');
    }
    
    // Поиск книг
    public function search() {
        $query = $_GET['q'] ?? '';
        
        if (empty($query)) {
            $this->redirect('/book-catalog/public/');
            return;
        }
        
        $books = $this->bookModel->search($query);
        $this->view('books/index', ['books' => $books, 'search' => $query]);
    }
}