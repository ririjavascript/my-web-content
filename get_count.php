<?php
header('Content-Type: application/json');
require_once 'config.php';

try {
    $buttonId = $_GET['button_id'] ?? null;

    if (!$buttonId) {
        throw new Exception('button_idは必須です');
    }

    // カウントを取得
    $stmt = $pdo->prepare("SELECT click_count FROM click_counts WHERE button_id = :button_id");
    $stmt->execute(['button_id' => $buttonId]);
    $result = $stmt->fetch();

    echo json_encode([
        'success' => true,
        'count' => $result ? $result['click_count'] : 0
    ]);

} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
?> 