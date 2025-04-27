<?php
// エラーを表示
ini_set('display_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json');
require_once 'config.php';

try {
    // リクエストメソッドの確認
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('POSTリクエストのみ受け付けます');
    }

    // 入力データの取得とログ
    $input = file_get_contents('php://input');
    error_log('Received input: ' . $input);

    $data = json_decode($input, true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        throw new Exception('Invalid JSON: ' . json_last_error_msg());
    }

    $buttonId = $data['button_id'] ?? null;
    error_log('Button ID: ' . $buttonId);

    if (!$buttonId) {
        throw new Exception('button_idは必須です');
    }

    // SQLクエリの実行
    $stmt = $pdo->prepare("INSERT INTO click_counts (button_id, click_count) 
                          VALUES (:button_id, 1) 
                          ON DUPLICATE KEY UPDATE click_count = click_count + 1");
    $stmt->execute(['button_id' => $buttonId]);
    error_log('Update query executed');

    // 更新後のカウントを取得
    $stmt = $pdo->prepare("SELECT click_count FROM click_counts WHERE button_id = :button_id");
    $stmt->execute(['button_id' => $buttonId]);
    $result = $stmt->fetch();
    error_log('Current count: ' . ($result['click_count'] ?? 'null'));

    $response = [
        'success' => true,
        'count' => $result['click_count']
    ];
    echo json_encode($response);
    error_log('Response sent: ' . json_encode($response));

} catch (Exception $e) {
    error_log('Error in update_count.php: ' . $e->getMessage());
    http_response_code(400);
    $response = [
        'success' => false,
        'error' => $e->getMessage()
    ];
    echo json_encode($response);
    error_log('Error response sent: ' . json_encode($response));
}
?> 