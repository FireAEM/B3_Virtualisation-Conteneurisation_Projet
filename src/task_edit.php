<?php
require_once __DIR__ . '/db.php';

try {
    $pdo = get_pdo();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $id = (int)($_POST['id'] ?? 0);
        $title = trim($_POST['title'] ?? '');
        $status = in_array($_POST['status'] ?? 'todo', ['todo','done']) ? $_POST['status'] : 'todo';

        if ($id && $title !== '') {
            $stmt = $pdo->prepare('UPDATE tasks SET title = ?, status = ? WHERE id = ?');
            $stmt->execute([$title, $status, $id]);
        }

        header('Location: /');
        exit;
    }

    $id = (int)($_GET['id'] ?? 0);
    $stmt = $pdo->prepare('SELECT id, title, status FROM tasks WHERE id = ?');
    $stmt->execute([$id]);
    $task = $stmt->fetch();

    if (!$task) {
        header('Location: /');
        exit;
    }

} catch (Exception $e) {
    http_response_code(500);
    echo "Internal error: " . htmlspecialchars($e->getMessage());
    exit;
}
?>

<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Edit task</title>
    <link rel="stylesheet" href="/assets/style.css">
</head>
<body>
    <div class="container">
        <h1>Edit task #<?=htmlspecialchars($task['id'])?></h1>
        <form method="post">
            <input type="hidden" name="id" value="<?=htmlspecialchars($task['id'])?>">
            <div>
                <input type="text" name="title" value="<?=htmlspecialchars($task['title'])?>" required>
            </div>
            <div>
                <select name="status">
                    <option value="todo" <?= $task['status']=='todo' ? 'selected' : '' ?>>todo</option>
                    <option value="done" <?= $task['status']=='done' ? 'selected' : '' ?>>done</option>
                </select>
            </div>
            <div>
                <button type="submit">Save</button> <a href="/">Cancel</a>
            </div>
        </form>
    </div>
</body>
</html>