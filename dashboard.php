<?php
session_start();
include 'db.php';
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$posts = $conn->query("SELECT * FROM posts WHERE user_id = $user_id");
?>
<!DOCTYPE html>
<html>
<head>
    <title>My Dashboard</title>
    <style>
        body { font-family: 'Segoe UI', Arial, sans-serif; background: #f0f2f5; margin: 0; }
        .container { max-width: 700px; margin: 40px auto; background: #fff; padding: 30px 40px; border-radius: 12px; box-shadow: 0 4px 24px #ccc; }
        .top-bar { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
        .btn { background: #007acc; color: #fff; padding: 8px 18px; border: none; border-radius: 6px; text-decoration: none; margin-right: 10px; transition: background 0.2s; }
        .btn:hover { background: #005fa3; }
        .btn-del { background: #b00; }
        .btn-del:hover { background: #900; }
        .post { border-bottom: 1px solid #eee; padding: 18px 0; }
        .post:last-child { border-bottom: none; }
        .title { font-size: 1.3em; color: #007acc; margin-bottom: 8px; }
        .content { color: #444; margin-bottom: 12px; }
        .actions { margin-top: 8px; }
        .no-posts { text-align: center; color: #888; font-size: 1.1em; margin: 40px 0; }
    </style>
</head>
<body>
    <div class="container">
        <div class="top-bar">
            <a class="btn" href="add_post.php">+ Add New Post</a>
            <a class="btn btn-del" href="logout.php">Logout</a>
        </div>
        <h1>Your Posts</h1>
        <?php if ($posts->num_rows > 0): ?>
            <?php while ($row = $posts->fetch_assoc()): ?>
                <div class="post">
                    <div class="title"><?= htmlspecialchars($row['title']) ?></div>
                    <div class="content"><?= nl2br(htmlspecialchars($row['content'])) ?></div>
                    <div class="actions">
                        <a class="btn" href="edit_post.php?id=<?= $row['id'] ?>">Edit</a>
                        <a class="btn btn-del" href="deletepost.php?id=<?= $row['id'] ?>" onclick="return confirm('Delete this post?')">Delete</a>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="no-posts">No posts found.</div>
        <?php endif; ?>
    </div>
</body>
</html>