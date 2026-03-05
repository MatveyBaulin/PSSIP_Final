<?php

class Model {
    protected $db;
    protected $table;
    
    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
    }
    
    // Получить все записи
    public function all() {
        $stmt = $this->db->query("SELECT * FROM {$this->table} ORDER BY created_at DESC");
        return $stmt->fetchAll();
    }
    
    // Найти по ID
    public function find($id) {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
    
    // Создать запись
    public function create($data) {
        $columns = implode(', ', array_keys($data));
        $placeholders = ':' . implode(', :', array_keys($data));
        
        $sql = "INSERT INTO {$this->table} ($columns) VALUES ($placeholders)";
        $stmt = $this->db->prepare($sql);
        
        return $stmt->execute($data);
    }
    
    // Обновить запись
    public function update($id, $data) {
        $set = '';
        foreach (array_keys($data) as $key) {
            $set .= "$key = :$key, ";
        }
        $set = rtrim($set, ', ');
        
        $sql = "UPDATE {$this->table} SET $set WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        
        $data['id'] = $id;
        return $stmt->execute($data);
    }
    
    // Удалить запись
    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE id = ?");
        return $stmt->execute([$id]);
    }
}