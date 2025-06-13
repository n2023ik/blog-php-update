<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

include 'db.php';
$msg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $stmt = $conn->prepare("SELECT id, password FROM users WHERE username=?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $hash);
        $stmt->fetch();
        if (password_verify($password, $hash)) {
            $_SESSION['user_id'] = $id;
            $_SESSION['username'] = $username;
            header("Location: index.php");
            exit;
        } else {
            $msg = "Invalid password.";
        }
    } else {
        $msg = "User not found. Username tried: " . htmlspecialchars($username);
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <style>
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            background: linear-gradient(120deg, #89f7fe 0%, #66a6ff 100%);
            margin: 0;
            min-height: 100vh;
        }
        .container {
            max-width: 400px;
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
            color: #007acc;
            margin-bottom: 28px;
            letter-spacing: 1px;
        }
        input {
            width: 100%;
            padding: 12px;
            margin: 12px 0;
            border-radius: 8px;
            border: 1px solid #b3c6e0;
            font-size: 1em;
            background: #f8fbff;
            transition: border 0.2s;
        }
        input:focus {
            border: 1.5px solid #007acc;
            outline: none;
        }
        .btn {
            width: 100%;
            background: linear-gradient(90deg, #007acc 60%, #005fa3 100%);
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
            background: linear-gradient(90deg, #005fa3 60%, #007acc 100%);
        }
        .msg {
            color: #b00;
            text-align: center;
            margin-bottom: 10px;
            font-weight: 500;
        }
        .link {
            text-align: center;
            display: block;
            margin-top: 18px;
            color: #007acc;
            text-decoration: none;
            font-size: 1em;
        }
        .link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>Login</h2>
    <?php if ($msg): ?><div class="msg"><?= htmlspecialchars($msg) ?></div><?php endif; ?>
    <form method="post" autocomplete="off">
        <input type="text" name="username" placeholder="Username" required>
        <input type="password" name="password" placeholder="Password" required>
        <button class="btn" type="submit">Login</button>
    </form>
    <a class="link" href="register.php">Don't have an account? Register</a>
</div>
</body>
</html>