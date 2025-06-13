<?php
include 'db.php';
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
$msg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $content = trim($_POST['content'] ?? '');
    $user_id = $_SESSION['user_id'];
    if ($title && $content) {
        $stmt = $conn->prepare("INSERT INTO posts (title, content, user_id) VALUES (?, ?, ?)");
        $stmt->bind_param("ssi", $title, $content, $user_id);
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
    <title>Add New Post</title>
    <style>
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            background: linear-gradient(120deg, #f093fb 0%, #f5576c 100%);
            margin: 0;
            min-height: 100vh;
        }
        .container {
            max-width: 500px;
            margin: 70px auto;
            background: #fff;
            padding: 36px 32px 28px 32px;
            border-radius: 16px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.12);
            animation: fadeIn 0.7s;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(30px);}
            to { opacity: 1; transform: translateY(0);}
        }
        h2 {
            text-align: center;
            color: #f5576c;
            margin-bottom: 28px;
            letter-spacing: 1px;
        }
        input, textarea {
            width: 100%;
            padding: 12px;
            margin: 12px 0;
            border-radius: 8px;
            border: 1px solid #f093fb;
            font-size: 1em;
            background: #f8fbff;
            transition: border 0.2s;
        }
        input:focus, textarea:focus {
            border: 1.5px solid #f5576c;
            outline: none;
        }
        textarea { resize: vertical; min-height: 120px; }
        .btn {
            width: 100%;
            background: linear-gradient(90deg, #f5576c 60%, #f093fb 100%);
            color: #fff;
            border: none;
            padding: 12px;
            border-radius: 8px;
            font-size: 1.1em;
            font-weight: 500;
            cursor: pointer;
            margin-top: 10px;
            transition: background 0.2s;
        }
        .btn:hover {
            background: linear-gradient(90deg, #f093fb 60%, #f5576c 100%);
        }
        .msg {
            color: #b00;
            text-align: center;
            margin-bottom: 10px;
            font-weight: 500;
        }
        .back-link {
            display: block;
            text-align: center;
            margin-top: 18px;
            color: #f5576c;
            text-decoration: none;
            font-size: 1em;
        }
        .back-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Add New Post</h2>
        <?php if ($msg): ?>
            <div class="msg"><?= htmlspecialchars($msg) ?></div>
        <?php endif; ?>
        <form method="post">
            <input type="text" name="title" placeholder="Post Title" required>
            <textarea name="content" placeholder="Write your post here..." required></textarea>
            <button class="btn" type="submit">Publish</button>
        </form>
        <a class="back-link" href="index.php">&larr; Back to Blog</a>
    </div>
</body>
</html>