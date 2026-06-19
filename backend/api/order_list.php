<?php
require 'db.php';
$user_id = intval($_GET['user_id'] ?? 0);
$status = $_GET['status'] ?? 'all';
$page = max(1, intval($_GET['page'] ?? 1));
$pageSize = max(1, intval($_GET['pageSize'] ?? 10));
$offset = ($page - 1) * $pageSize;
if (!$user_id) {
    jsonResponse(1, null, '参数错误');
}
$query = 'SELECT id, order_no, user_id, goods_info, total_price, status, create_time FROM orders WHERE user_id = ?';
$params = [$user_id];
if ($status !== 'all') {
    $query .= ' AND status = ?';
    $params[] = $status;
}
$query .= ' ORDER BY create_time DESC LIMIT ?, ?';
if ($status !== 'all') {
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param('isii', $params[0], $params[1], $offset, $pageSize);
} else {
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param('iii', $params[0], $offset, $pageSize);
}
$stmt->execute();
$result = $stmt->get_result();
$orders = [];
while ($row = $result->fetch_assoc()) {
    $row['goods_info'] = json_decode($row['goods_info'], true);
    $orders[] = $row;
}
jsonResponse(0, $orders, '订单列表');
?>