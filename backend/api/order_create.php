<?php
require 'db.php';
$user_id = intval($_POST['user_id'] ?? 0);
$goods = $_POST['goods'] ?? '';
$total_price = floatval($_POST['total_price'] ?? 0);
$status = $_POST['status'] ?? '待付款';
if (!$user_id || !$goods || $total_price <= 0) {
    jsonResponse(1, null, '参数错误');
}
$order_no = 'ORDER' . time() . rand(100, 999);
$stmt = $mysqli->prepare('INSERT INTO orders (order_no, user_id, goods_info, total_price, status, create_time) VALUES (?, ?, ?, ?, ?, NOW())');
$stmt->bind_param('sisds', $order_no, $user_id, $goods, $total_price, $status);
$stmt->execute();
if ($stmt->affected_rows > 0) {
    jsonResponse(0, ['order_no' => $order_no], '订单创建成功');
}
jsonResponse(1, null, '创建订单失败');
?>