<?php
function loadEnv($file = '/var/www/html/.env') {
    if (!file_exists($file)) {
        die("Arquivo .env não encontrado! Caminho tentado: " . $file);
    }

    $lines = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos($line, '=') !== false) {
            list($key, $value) = explode('=', $line, 2);
            putenv(trim($key) . '=' . trim($value));
        }
    }
}

// Carregar variáveis do .env
loadEnv();
?>
