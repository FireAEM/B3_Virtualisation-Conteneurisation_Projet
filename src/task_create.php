<?php
require_once __DIR__ . '/db.php';

try {
    $pdo = get_pdo();

    $title = trim($_POST['title'] ?? '');
    $status = in_array($_POST['status'] ?? 'todo', ['todo','done']) ? $_POST['status'] : 'todo';

    if ($title !== '') {
        $stmt = $pdo->prepare('INSERT INTO tasks (title, status) VALUES (?, ?)');
        $stmt->execute([$title, $status]);
    }

    header('Location: /');
    exit;

} catch (Exception $e) {
    http_response_code(500);
    echo "Internal error: " . htmlspecialchars($e->getMessage());
    exit;
}