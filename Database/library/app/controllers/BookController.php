<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\Book;

class BookController extends Controller {
    private $bookModel;

    public function __construct() {
        $this->bookModel = $this->model('Book');
    }

    // Главная страница - список книг
    public function index() {
        $books = $this->bookModel->getAll();
        $this->view('books/index', [
            'books' => $books,
            'title' => 'Каталог книг'
        ]);
    }

    // Показать форму создания книги
    public function create() {
        $this->view('books/create', [
            'title' => 'Добавить книгу'
        ]);
    }

    // Сохранить новую книгу
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/');
        }

        $data = [
            'title' => $_POST['title'] ?? '',
            'author' => $_POST['author'] ?? '',
            'year' => !empty($_POST['year']) ? (int)$_POST['year'] : null,
            'genre' => $_POST['genre'] ?? '',
            'description' => $_POST['description'] ?? ''
        ];

        // Валидация
        $errors = [];
        if (empty($data['title'])) {
            $errors[] = 'Название книги обязательно';
        }
        if (empty($data['author'])) {
            $errors[] = 'Автор обязателен';
        }

        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $_SESSION['old'] = $data;
            $this->redirect('/books/create');
            return;
        }

        if ($this->bookModel->create($data)) {
            $_SESSION['success'] = 'Книга успешно добавлена';
            $this->redirect('/');
        } else {
            $_SESSION['error'] = 'Ошибка при добавлении книги';
            $this->redirect('/books/create');
        }
    }

    // Показать одну книгу
    public function show($id) {
        $book = $this->bookModel->getById($id);
        
        if (!$book) {
            $this->redirect('/');
            return;
        }

        $this->view('books/show', [
            'book' => $book,
            'title' => $book['title']
        ]);
    }

    // Показать форму редактирования
    public function edit($id) {
        $book = $this->bookModel->getById($id);
        
        if (!$book) {
            $this->redirect('/');
            return;
        }

        $this->view('books/edit', [
            'book' => $book,
            'title' => 'Редактировать: ' . $book['title']
        ]);
    }

    // Обновить книгу
    public function update($id) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/');
        }

        $data = [
            'title' => $_POST['title'] ?? '',
            'author' => $_POST['author'] ?? '',
            'year' => !empty($_POST['year']) ? (int)$_POST['year'] : null,
            'genre' => $_POST['genre'] ?? '',
            'description' => $_POST['description'] ?? ''
        ];

        // Валидация
        $errors = [];
        if (empty($data['title'])) {
            $errors[] = 'Название книги обязательно';
        }
        if (empty($data['author'])) {
            $errors[] = 'Автор обязателен';
        }

        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $_SESSION['old'] = $data;
            $this->redirect("/books/edit/$id");
            return;
        }

        if ($this->bookModel->update($id, $data)) {
            $_SESSION['success'] = 'Книга успешно обновлена';
            $this->redirect("/books/show/$id");
        } else {
            $_SESSION['error'] = 'Ошибка при обновлении книги';
            $this->redirect("/books/edit/$id");
        }
    }

    // Удалить книгу
    public function delete($id) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/');
        }

        if ($this->bookModel->delete($id)) {
            $_SESSION['success'] = 'Книга удалена';
        } else {
            $_SESSION['error'] = 'Ошибка при удалении';
        }

        $this->redirect('/');
    }

    // Поиск книг
    public function search() {
        $query = $_GET['q'] ?? '';
        
        if (empty($query)) {
            $this->redirect('/');
            return;
        }

        $books = $this->bookModel->search($query);
        
        $this->view('books/index', [
            'books' => $books,
            'title' => 'Результаты поиска: ' . $query,
            'search' => $query
        ]);
    }

    // Книги по автору
    public function byAuthor($author) {
        $author = urldecode($author);
        $books = $this->bookModel->getByAuthor($author);
        
        $this->view('books/index', [
            'books' => $books,
            'title' => 'Книги автора: ' . $author
        ]);
    }

    // Книги по жанру
    public function byGenre($genre) {
        $genre = urldecode($genre);
        $books = $this->bookModel->getByGenre($genre);
        
        $this->view('books/index', [
            'books' => $books,
            'title' => 'Жанр: ' . $genre
        ]);
    }

    // Статистика
    public function stats() {
        $stats = $this->bookModel->getStats();
        
        $this->json($stats);
    }

    // API: получить все книги в JSON
    public function apiIndex() {
        $books = $this->bookModel->getAll();
        $this->json($books);
    }

    // API: получить книгу по ID
    public function apiShow($id) {
        $book = $this->bookModel->getById($id);
        
        if ($book) {
            $this->json($book);
        } else {
            $this->json(['error' => 'Книга не найдена']);
        }
    }
}
?>