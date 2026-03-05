<?php
namespace App\Models;

use App\Core\Database;

class Book {
    private $db;
    private $table = 'books';

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    // Получить все книги
    public function getAll() {
        $stmt = $this->db->query("SELECT * FROM {$this->table} ORDER BY created_at DESC");
        return $stmt->fetchAll();
    }

    // Получить книгу по ID
    public function getById($id) {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    // Создать книгу
    public function create($data) {
        $sql = "INSERT INTO {$this->table} (title, author, year, genre, description) 
                VALUES (:title, :author, :year, :genre, :description)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($data);
    }

    // Обновить книгу
    public function update($id, $data) {
        $sql = "UPDATE {$this->table} 
                SET title = :title, author = :author, year = :year, 
                    genre = :genre, description = :description 
                WHERE id = :id";
        $data['id'] = $id;
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($data);
    }

    // Удалить книгу
    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE id = ?");
        return $stmt->execute([$id]);
    }

    // Поиск книг
    public function search($query) {
        $sql = "SELECT * FROM {$this->table} 
                WHERE title LIKE :query OR author LIKE :query OR genre LIKE :query 
                ORDER BY created_at DESC";
        $stmt = $this->db->prepare($sql);
        $searchTerm = "%$query%";
        $stmt->execute([
            ':query' => $searchTerm,
            ':query' => $searchTerm,
            ':query' => $searchTerm
        ]);
        return $stmt->fetchAll();
    }

    // Получить книги по автору
    public function getByAuthor($author) {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE author = ? ORDER BY year");
        $stmt->execute([$author]);
        return $stmt->fetchAll();
    }

    // Получить книги по жанру
    public function getByGenre($genre) {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE genre = ? ORDER BY title");
        $stmt->execute([$genre]);
        return $stmt->fetchAll();
    }

    // Получить уникальных авторов
    public function getAuthors() {
        $stmt = $this->db->query("SELECT DISTINCT author FROM {$this->table} ORDER BY author");
        return $stmt->fetchAll(\PDO::FETCH_COLUMN);
    }

    // Получить уникальные жанры
    public function getGenres() {
        $stmt = $this->db->query("SELECT DISTINCT genre FROM {$this->table} ORDER BY genre");
        return $stmt->fetchAll(\PDO::FETCH_COLUMN);
    }

    // Получить статистику
    public function getStats() {
        $stats = [];
        
        // Общее количество книг
        $stmt = $this->db->query("SELECT COUNT(*) as total FROM {$this->table}");
        $stats['total'] = $stmt->fetch()['total'];
        
        // Книг по авторам
        $stmt = $this->db->query("SELECT author, COUNT(*) as count 
                                   FROM {$this->table} 
                                   GROUP BY author 
                                   ORDER BY count DESC 
                                   LIMIT 5");
        $stats['top_authors'] = $stmt->fetchAll();
        
        // Книг по жанрам
        $stmt = $this->db->query("SELECT genre, COUNT(*) as count 
                                   FROM {$this->table} 
                                   GROUP BY genre 
                                   ORDER BY count DESC");
        $stats['by_genre'] = $stmt->fetchAll();
        
        // Диапазон годов
        $stmt = $this->db->query("SELECT MIN(year) as min_year, MAX(year) as max_year 
                                   FROM {$this->table} WHERE year IS NOT NULL");
        $stats['years'] = $stmt->fetch();
        
        return $stats;
    }
}
?>