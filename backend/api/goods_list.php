<?php
require 'db.php';
$keyword = $_GET['keyword'] ?? '';
$page = max(1, intval($_GET['page'] ?? 1));
$pageSize = max(1, intval($_GET['pageSize'] ?? 10));
$offset = ($page - 1) * $pageSize;
$query = 'SELECT id, name, price, cover, detail, stock, category_id FROM goods';
$params = [];
if ($keyword) {
    $query .= ' WHERE name LIKE ?';
    $keywordParam = "%{$keyword}%";
    $params[] = $keywordParam;
}
$query .= ' LIMIT ?, ?';
$stmt = $mysqli->prepare($query);
if ($keyword) {
    $stmt->bind_param('sii', $keywordParam, $offset, $pageSize);
} else {
    $stmt->bind_param('ii', $offset, $pageSize);
}
$stmt->execute();
$result = $stmt->get_result();
$goods = [];
while ($row = $result->fetch_assoc()) {
    $row['cover'] = $row['cover'];
    $goods[] = $row;
}
jsonResponse(0, $goods, '商品列表');
?>