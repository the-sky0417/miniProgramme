<?php
require 'db.php';
$action = $_POST['action'] ?? $_GET['action'] ?? '';
if (!$action) {
    jsonResponse(1, null, '缺少 action');
}

if ($action === 'list') {
    $user_id = $_POST['user_id'] ?? $_GET['user_id'] ?? 0;
    if (!$user_id) jsonResponse(1, null, '缺少 user_id');
    $stmt = $mysqli->prepare('SELECT sc.id, sc.user_id, sc.goods_id, sc.quantity, g.name, g.price, g.cover FROM shopping_cart sc LEFT JOIN goods g ON sc.goods_id = g.id WHERE sc.user_id = ?');
    $stmt->bind_param('i', $user_id);
    $stmt->execute();
    $res = $stmt->get_result();
    $list = [];
    while ($row = $res->fetch_assoc()) {
        $list[] = $row;
    }
    jsonResponse(0, $list, 'ok');
}

if ($action === 'add') {
    $user_id = $_POST['user_id'] ?? 0;
    $goods_id = $_POST['goods_id'] ?? 0;
    $quantity = $_POST['quantity'] ?? 1;
    if (!$user_id || !$goods_id) jsonResponse(1, null, '参数不完整');
    // try update
    $stmt = $mysqli->prepare('SELECT id, quantity FROM shopping_cart WHERE user_id = ? AND goods_id = ?');
    $stmt->bind_param('ii', $user_id, $goods_id);
    $stmt->execute();
    $res = $stmt->get_result();
    if ($res->num_rows > 0) {
        $row = $res->fetch_assoc();
        $newQ = $row['quantity'] + $quantity;
        $stmt = $mysqli->prepare('UPDATE shopping_cart SET quantity = ?, update_time = NOW() WHERE id = ?');
        $stmt->bind_param('ii', $newQ, $row['id']);
        $stmt->execute();
        jsonResponse(0, ['id' => $row['id'], 'quantity' => $newQ], '更新购物车成功');
    } else {
        $stmt = $mysqli->prepare('INSERT INTO shopping_cart (user_id, goods_id, quantity, create_time, update_time) VALUES (?, ?, ?, NOW(), NOW())');
        $stmt->bind_param('iii', $user_id, $goods_id, $quantity);
        $stmt->execute();
        $id = $stmt->insert_id;
        jsonResponse(0, ['id' => $id, 'quantity' => $quantity], '加入购物车成功');
    }
}

if ($action === 'update') {
    $id = $_POST['id'] ?? 0;
    $quantity = $_POST['quantity'] ?? null;
    if (!$id || $quantity === null) jsonResponse(1, null, '参数不完整');
    $stmt = $mysqli->prepare('UPDATE shopping_cart SET quantity = ?, update_time = NOW() WHERE id = ?');
    $stmt->bind_param('ii', $quantity, $id);
    $stmt->execute();
    jsonResponse(0, null, '更新成功');
}

if ($action === 'remove') {
    $id = $_POST['id'] ?? 0;
    if (!$id) jsonResponse(1, null, '参数不完整');
    $stmt = $mysqli->prepare('DELETE FROM shopping_cart WHERE id = ?');
    $stmt->bind_param('i', $id);
    $stmt->execute();
    jsonResponse(0, null, '删除成功');
}

jsonResponse(1, null, '未知 action');
?>
