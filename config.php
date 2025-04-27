<?php
define('DB_HOST', 'mysql309.phy.lolipop.lan');
define('DB_NAME', 'LA11152842-vote');
define('DB_USER', 'LA11152842');
define('DB_PASS', 'vote2024');

try {
    $pdo = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
        DB_USER,
        DB_PASS,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]
    );
} catch(PDOException $e) {
    error_log("データベース接続エラー: " . $e->getMessage());
    die("データベースに接続できませんでした。");
}
?> 