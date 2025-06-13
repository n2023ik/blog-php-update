<?php
include 'db.php';
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
$user_id = $_SESSION['user_id'];
$id = intval($_GET['id'] ?? 0);
$msg = '';
// Fetch post
$stmt = $conn->prepare("SELECT * FROM posts WHERE id=? AND user_id=?");
$stmt->bind_param("ii", $id, $user_id);
$stmt->execute();
$result = $stmt->get_result();
$post = $result->fetch_assoc();
$stmt->close();
if (!$post) {
    die("Post not found or access denied.");
}
// Update post
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $content = trim($_POST['content'] ?? '');
    if ($title && $content) {
        $stmt = $conn->prepare("UPDATE posts SET title=?, content=? WHERE id=? AND user_id=?");
        $stmt->bind_param("ssii", $title, $content, $id, $user_id);
        if ($stmt->execute()) {
            header("Location: index.php");
            exit;
        } else {
            $msg = "Error: " . $stmt->error;
        }
        $stmt->close();
    } else {
        $msg = "Please fill in all fields.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit Post</title>
    <style>
        body { font-family: 'Segoe UI', Arial, sans-serif; background: #f0f2f5; margin: 0; }
        .container { max-width: 500px; margin: 60px auto; background: #fff; padding: 30px 40px; border-radius: 12px; box-shadow: 0 4px 24px #ccc; }
        h2 { text-align: center; color: #222; margin-bottom: 30px; }
        form { display: flex; flex-direction: column; }
        input, textarea { margin-bottom: 18px; padding: 10px; border: 1px solid #ccc; border-radius: 6px; font-size: 1em; }
        textarea { resize: vertical; min-height: 100px; }
        .btn {
            background: #007acc;
            color: #fff;
            padding: 10px 22px;
            border: none;
            border-radius: 6px;
            font-size: 1em;
            cursor: pointer;
            transition: background 0.2s;
        }
        .btn:hover { background: #005fa3; }
        .msg { color: #b00; text-align: center; margin-bottom: 15px; }
        .back-link { display: block; text-align: center; margin-top: 18px; color: #007acc; text-decoration: none; }
        .back-link:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Edit Post</h2>
        <?php if ($msg): ?>
            <div class="msg"><?= htmlspecialchars($msg) ?></div>
        <?php endif; ?>
        <form method="post">
            <input type="text" name="title" value="<?= htmlspecialchars($post['title']) ?>" required>
            <textarea name="content" required><?= htmlspecialchars($post['content']) ?></textarea>
            <button class="btn" type="submit">Update</button>
        </form>
        <a class="back-link" href="index.php">&larr; Back to Blog</a>
    </div>
</body>
</html>