<?php
// app/models/Book.php
require_once "../app/core/Model.php";

class Book extends Model {
    protected $table = 'books';
    
    // Поиск книг
    public function search($query) {
        $stmt = $this->db->prepare(
            "SELECT * FROM {$this->table} 
             WHERE title LIKE :query OR author LIKE :query 
             ORDER BY created_at DESC"
        );
        $stmt->execute([':query' => "%$query%"]);
        return $stmt->fetchAll();
    }
    
    // Получить книги по автору
    public function getByAuthor($author) {
        $stmt = $this->db->prepare(
            "SELECT * FROM {$this->table} WHERE author = ? ORDER BY year"
        );
        $stmt->execute([$author]);
        return $stmt->fetchAll();
    }
}