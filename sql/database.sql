-- Criar Banco de Dados
CREATE DATABASE php_bank;

-- Selecionar o Banco de Dados
\c php_bank;

-- Criar Tabela de Usuários
CREATE TABLE users (
    user_id SERIAL PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    cpf VARCHAR(11) UNIQUE NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    photo VARCHAR(255) DEFAULT 'default.jpg',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    deleted_at TIMESTAMP DEFAULT NULL
);

-- Criar Tabela de Contas Bancárias
CREATE TABLE wallets (
    wallet_id SERIAL PRIMARY KEY,
    user_id INT REFERENCES users(user_id) ON DELETE CASCADE,
    account_number VARCHAR(20) UNIQUE NOT NULL,
    balance DECIMAL(10,2) DEFAULT 0.00 NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Criar Tabela de Transações
CREATE TABLE transactions (
    transaction_id SERIAL PRIMARY KEY,
    wallet_sender_id INT REFERENCES wallets(wallet_id) ON DELETE SET NULL,
    wallet_receiver_id INT REFERENCES wallets(wallet_id) ON DELETE SET NULL,
    amount DECIMAL(10,2) NOT NULL,
    status VARCHAR(20) DEFAULT 'concluída',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
