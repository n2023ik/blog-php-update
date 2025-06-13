<?php
include 'db.php';
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];

// Fetch posts
$posts = [];
$sql = "SELECT posts.*, users.username FROM posts LEFT JOIN users ON posts.user_id = users.id ORDER BY created_at DESC";
$result = $conn->query($sql);
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $posts[] = $row;
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Dashboard - My Simple Blog</title>
    <style>
        body { font-family: 'Segoe UI', Arial, sans-serif; background: #f0f2f5; margin: 0; }
        .container { max-width: 700px; margin: 40px auto; background: #fff; padding: 30px 40px; border-radius: 12px; box-shadow: 0 4px 24px #ccc; }
        h1 { text-align: center; color: #222; margin-bottom: 30px; }
        .top-bar { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
        .welcome { color: #007acc; }
        .logout { color: #b00; text-decoration: none; }
        .logout:hover { text-decoration: underline; }
        .post { border-bottom: 1px solid #eee; padding: 24px 0; }
        .post:last-child { border-bottom: none; }
        .title { font-size: 1.3em; color: #007acc; margin-bottom: 8px; }
        .content { color: #444; margin-bottom: 12px; }
        .meta { color: #888; font-size: 0.95em; }
        .actions { margin-top: 10px; }
        .btn { background: #007acc; color: #fff; padding: 6px 14px; border: none; border-radius: 5px; margin-right: 6px; text-decoration: none; }
        .btn:hover { background: #005fa3; }
        .btn-del { background: #b00; }
        .btn-del:hover { background: #900; }
        .add-post-btn {
            background: #007acc;
            color: #fff;
            padding: 10px 22px;
            border: none;
            border-radius: 6px;
            font-size: 1em;
            text-decoration: none;
            transition: background 0.2s;
        }
        .add-post-btn:hover { background: #005fa3; }
        .no-posts { text-align: center; color: #888; font-size: 1.1em; margin: 40px 0; }
    </style>
</head>
<body>
    <div class="container">
        <div class="top-bar">
            <span class="welcome">Welcome, <?= htmlspecialchars($username) ?></span>
            <a class="logout" href="logout.php">Logout</a>
        </div>
        <h1>My Simple Blog</h1>
        <a class="add-post-btn" href="add_post.php">+ Add New Post</a>
        <?php if (count($posts) > 0): ?>
            <?php foreach ($posts as $post): ?>
                <div class="post">
                    <div class="title"><?= htmlspecialchars($post['title']) ?></div>
                    <div class="content"><?= nl2br(htmlspecialchars($post['content'])) ?></div>
                    <div class="meta">
                        Posted by <?= htmlspecialchars($post['username'] ?? 'Unknown') ?> on <?= htmlspecialchars($post['created_at']) ?>
                    </div>
                    <?php if ($post['user_id'] == $user_id): ?>
                        <div class="actions">
                            <a class="btn" href="edit_post.php?id=<?= $post['id'] ?>">Edit</a>
                            <a class="btn btn-del" href="deletepost.php?id=<?= $post['id'] ?>" onclick="return confirm('Delete this post?')">Delete</a>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="no-posts">No posts found.</div>
        <?php endif; ?>
    </div>
</body>
</html>