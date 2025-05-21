<?php

require_once 'Database.php';

class User {
    private $conn;

    public function __construct()
    {
        $db = new Database();
        $this->conn = $db->connect();
    }

    public function register($name, $cpf, $email, $password, $photo) {
        $sql = "INSERT INTO users (name, cpf, email, password, photo) VALUES (:name, :cpf, :email, :password, :photo)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ":name" => $name,
            ":cpf" => $cpf,
            ":email" => $email,
            ":password" => password_hash($password, PASSWORD_DEFAULT),
            ":photo" => $photo ?? 'default.jpg'
        ]);
    }

    public function updateUser($userId, $name, $photo) {
        $sql = "UPDATE users SET name = :name, photo = :photo, updated_at = CURRENT_TIMESTAMP WHERE user_id = :user_id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ":name" => $name,
            ":photo" => $photo,
            ":user_id" => $userId
        ]);
    }

    public function deleteUser($userId) {
        $sql = "UPDATE users SET deleted_at = CURRENT_TIMESTAMP WHERE user_id = :user_id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([":user_id" => $userId]);
    }
}