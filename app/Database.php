<?php

require_once 'config.php';

class Database {
    private $host;
    private $dbname;
    private $user;
    private $pass;
    private $conn;

    public function __construct() {
        $this->host = getenv("DB_HOST");
        $this->dbname = getenv("DB_NAME");
        $this->user = getenv("DB_USER");
        $this->pass = getenv("DB_PASS");
    }

    public function connect() {
        try {
            $this->conn = new PDO("pgsql:host=$this->host;dbname=$this->dbname", $this->user, $this->pass);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $this->conn;
        } catch (PDOException $e) {
            die(json_encode(["status" => "error", "message" => "Erro na conexão: " . $e->getMessage()]));
        }
    }
}
?>
