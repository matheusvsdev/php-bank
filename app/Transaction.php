<?php

require_once 'Database.php';

class Transaction {
    private $conn;

    public function __construct() {
        $db = new Database();
        $this->conn = $db->connect();
    }

    public function transfer($senderWalletId, $receiverWalletId, $amount) {
        try {
            $this->conn->beginTransaction();

            // Verificar saldo disponível
            $sql = "SELECT balance FROM wallets WHERE wallet_id = :wallet_id";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([":wallet_id" => $senderWalletId]);
            $senderBalance = $stmt->fetch(PDO::FETCH_ASSOC)['balance'];

            if ($senderBalance < $amount) {
                throw new Exception("Saldo insuficiente.");
            }

            // Atualizar saldo da conta de origem
            $sql = "UPDATE wallets SET balance = balance - :amount WHERE wallet_id = :wallet_id";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([":amount" => $amount, ":wallet_id" => $senderWalletId]);

            // Atualizar saldo da conta de destino
            $sql = "UPDATE wallets SET balance = balance + :amount WHERE wallet_id = :wallet_id";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([":amount" => $amount, ":wallet_id" => $receiverWalletId]);

            // Registrar transação
            $sql = "INSERT INTO transactions (wallet_sender_id, wallet_receiver_id, amount, status) VALUES (:sender_wallet_id, :receiver_wallet_id, :amount, 'concluída')";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                ":sender_wallet_id" => $senderWalletId,
                ":receiver_wallet_id" => $receiverWalletId,
                ":amount" => $amount
            ]);

            $this->conn->commit();
            return ["status" => "success", "message" => "Transferência concluída!"];
        } catch (Exception $e) {
            $this->conn->rollBack();
            return ["status" => "error", "message" => $e->getMessage()];
        }
    }

    public function getTransactionHistory($walletId) {
        $sql = "SELECT * FROM transactions WHERE wallet_sender_id = :wallet_id OR wallet_receiver_id = :wallet_id ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([":wallet_id" => $walletId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
