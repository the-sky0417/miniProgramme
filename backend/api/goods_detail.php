<?php
require 'db.php';
$id = intval($_GET['id'] ?? 0);
if (!$id) {
    jsonResponse(1, null, '参数错误');
}
$stmt = $mysqli->prepare('SELECT id, name, price, cover, detail, stock, category_id FROM goods WHERE id = ?');
$stmt->bind_param('i', $id);
$stmt->execute();
$result = $stmt->get_result();
if ($row = $result->fetch_assoc()) {
    jsonResponse(0, $row, '商品详情');
}
jsonResponse(1, null, '商品不存在');
?>