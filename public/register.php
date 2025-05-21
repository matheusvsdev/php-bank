<?php

require __DIR__ . '/../app/User.php';
require __DIR__ . '/../app/Wallet.php';

header("Content-Type: application/json");

$data = json_decode(file_get_contents("php://input"), true);
if (!isset($data['name'], $data['cpf'], $data['email'], $data['password'])) {
    echo json_encode(["status" => "error", "message" => "Campos obrigatórios faltando"]);
    exit();
}

$user = new User();
if ($user->register($data['name'], $data['cpf'], $data['email'], $data['password'], $data['photo'] ?? null)) {
    $db = new Database();
    $conn = $db->connect();

    $stmt = $conn->prepare("SELECT user_id FROM users WHERE email = :email");
    $stmt->execute([":email" => $data['email']]);
    $userId = $stmt->fetch(PDO::FETCH_ASSOC)['user_id'];

    $wallet = new Wallet();
    if ($wallet->createWallet($userId)) {
        echo json_encode(["status" => "success", "message" => "Usuário e conta criados com sucesso"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Erro ao criar conta bancária"]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Erro ao cadastrar usuário"]);
}
?>
