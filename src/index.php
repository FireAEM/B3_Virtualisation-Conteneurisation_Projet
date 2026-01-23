<?php
require_once __DIR__ . '/db.php';

try {
    $pdo = get_pdo();

    // Delete (GET pour simplicité; on peut remplacer par POST si souhaité)
    if (isset($_GET['delete'])) {
        $id = (int)$_GET['delete'];
        $stmt = $pdo->prepare('DELETE FROM tasks WHERE id = ?');
        $stmt->execute([$id]);
        header('Location: /');
        exit;
    }

    // Fetch tasks
    $stmt = $pdo->query('SELECT id, title, status FROM tasks ORDER BY id DESC');
    $tasks = $stmt->fetchAll();

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
    <title>Task App</title>
    <link rel="stylesheet" href="/assets/style.css">
</head>
<body>
    <div class="container">
        <h1>Task list</h1>

        <form action="/task_create.php" method="post" class="form-inline">
            <input type="text" name="title" placeholder="New task title" required>
            <select name="status">
                <option value="todo">todo</option>
                <option value="done">done</option>
            </select>
            <button type="submit">Add</button>
        </form>

        <table>
            <thead><tr><th>ID</th><th>Title</th><th>Status</th><th>Actions</th></tr></thead>
            <tbody>
                <?php foreach ($tasks as $t): ?>
                    <tr>
                        <td><?=htmlspecialchars($t['id'])?></td>
                        <td><?=htmlspecialchars($t['title'])?></td>
                        <td><?=htmlspecialchars($t['status'])?></td>
                        <td>
                            <a href="/task_edit.php?id=<?=urlencode($t['id'])?>">Edit</a>
                            <a href="/?delete=<?=urlencode($t['id'])?>" onclick="return confirm('Delete?')">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>