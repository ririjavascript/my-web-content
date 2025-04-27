<?php
header('Content-Type: application/json');

// 本番環境用のデータベース設定
$host = 'localhost';  // データベースのホスト名
$dbname = 'click_count_db';  // データベース名
$username = 'root';  // データベースのユーザー名
$password = '';  // データベースのパスワード

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

    // テーブルが存在しない場合は作成
    $pdo->exec("CREATE TABLE IF NOT EXISTS votes (
        id INT AUTO_INCREMENT PRIMARY KEY,
        vote_type VARCHAR(10) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $data = json_decode(file_get_contents('php://input'), true);
        if (!isset($data['vote'])) {
            throw new Exception('投票データが不正です');
        }
        $vote = $data['vote'];
        
        // 投票を記録
        $stmt = $pdo->prepare("INSERT INTO votes (vote_type) VALUES (?)");
        $stmt->execute([$vote]);
    }

    // 現在の投票数を取得
    $stmt = $pdo->query("SELECT 
        SUM(CASE WHEN vote_type = 'yes' THEN 1 ELSE 0 END) as yes_count,
        SUM(CASE WHEN vote_type = 'no' THEN 1 ELSE 0 END) as no_count
        FROM votes");
    $result = $stmt->fetch();

    // NULLの場合は0に変換
    echo json_encode([
        'yes' => (int)($result['yes_count'] ?? 0),
        'no' => (int)($result['no_count'] ?? 0)
    ]);

} catch(PDOException $e) {
    error_log('Database Error: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode(['error' => 'データベースエラーが発生しました']);
} catch(Exception $e) {
    error_log('Application Error: ' . $e->getMessage());
    http_response_code(400);
    echo json_encode(['error' => $e->getMessage()]);
} 