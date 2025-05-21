<?php

require_once 'Database.php';

class Wallet {
    private $conn;

    public function __construct() {
        $db = new Database();
        $this->conn = $db->connect();
    }

    public function createWallet($userId) {
        $accountNumber = rand(10000000, 99999999);
        $sql = "INSERT INTO wallets (user_id, account_number, balance) VALUES (:user_id, :account_number, :balance)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ":user_id" => $userId,
            ":account_number" => $accountNumber,
            ":balance" => 0.00
        ]);
    }

    public function getBalance($walletId) {
        $sql = "SELECT balance FROM wallets WHERE wallet_id = :wallet_id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([":wallet_id" => $walletId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>
