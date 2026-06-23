<?php
session_start();
require __DIR__ . '/../api/db.php';
if (empty($_SESSION['admin_logged'])) {
    header('Location: login.php');
    exit;
}
$counts = [];
$res = $mysqli->query('SELECT COUNT(*) as cnt FROM user');
if ($res) { $row = $res->fetch_assoc(); $counts['users'] = $row['cnt']; }
$res = $mysqli->query('SELECT COUNT(*) as cnt FROM goods');
if ($res) { $row = $res->fetch_assoc(); $counts['goods'] = $row['cnt']; }
$res = $mysqli->query('SELECT COUNT(*) as cnt FROM orders');
if ($res) { $row = $res->fetch_assoc(); $counts['orders'] = $row['cnt']; }
?>
<!doctype html>
<html lang="zh-CN">
<head>
  <meta charset="utf-8">
  <title>后台管理</title>
</head>
<body>
  <h1>后台仪表盘</h1>
  <p>用户数：<?php echo htmlspecialchars($counts['users'] ?? 0); ?></p>
  <p>商品数：<?php echo htmlspecialchars($counts['goods'] ?? 0); ?></p>
  <p>订单数：<?php echo htmlspecialchars($counts['orders'] ?? 0); ?></p>
  <p><a href="logout.php">登出</a></p>
</body>
</html>
