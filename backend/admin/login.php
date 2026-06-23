<?php
session_start();
require __DIR__ . '/../api/db.php';
if (isset($_POST['username']) && isset($_POST['password'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $stmt = $mysqli->prepare('SELECT id, username, password FROM admin WHERE username = ? LIMIT 1');
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $res = $stmt->get_result();
    if ($res->num_rows > 0) {
        $row = $res->fetch_assoc();
        if ($password === $row['password']) {
            $_SESSION['admin_logged'] = true;
            $_SESSION['admin_id'] = $row['id'];
            header('Location: index.php');
            exit;
        }
    }
    $error = '用户名或密码错误';
}
?>
<!doctype html>
<html lang="zh-CN">
<head>
  <meta charset="utf-8">
  <title>后台登录</title>
</head>
<body>
  <h2>管理员登录</h2>
  <?php if (!empty($error)) echo '<p style="color:red;">' . htmlspecialchars($error) . '</p>'; ?>
  <form method="post" action="">
    <label>用户名：<input type="text" name="username" required></label><br>
    <label>密码：<input type="password" name="password" required></label><br>
    <button type="submit">登录</button>
  </form>
</body>
</html>
