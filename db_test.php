<?php
header('Content-Type: text/html; charset=utf-8');
require_once 'config.php';

echo "<h2>データベース接続テスト</h2>";

try {
    // データベース接続テスト
    echo "<p>データベース接続: 成功</p>";
    
    // テーブル内容の取得
    $stmt = $pdo->query("SELECT * FROM click_counts");
    $results = $stmt->fetchAll();
    
    echo "<h3>クリックカウント一覧:</h3>";
    echo "<table border='1'>";
    echo "<tr><th>ID</th><th>ボタンID</th><th>クリック回数</th><th>最終更新</th></tr>";
    
    foreach ($results as $row) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($row['id']) . "</td>";
        echo "<td>" . htmlspecialchars($row['button_id']) . "</td>";
        echo "<td>" . htmlspecialchars($row['click_count']) . "</td>";
        echo "<td>" . htmlspecialchars($row['last_updated']) . "</td>";
        echo "</tr>";
    }
    
    echo "</table>";

} catch (PDOException $e) {
    echo "<p style='color: red'>エラー: " . htmlspecialchars($e->getMessage()) . "</p>";
}
?> 