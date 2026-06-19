<?php
require 'db.php';
$nickName = $_POST['nickName'] ?? '';
$avatarUrl = $_POST['avatarUrl'] ?? '';
$code = $_POST['code'] ?? '';
if (!$nickName || !$avatarUrl || !$code) {
    jsonResponse(1, null, '参数不完整');
}
$openid = 'openid_' . md5($code);
$stmt = $mysqli->prepare('SELECT id, wechat_name, avatar, openid, create_time FROM user WHERE openid = ?');
$stmt->bind_param('s', $openid);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    jsonResponse(0, $user, '登录成功');
}
$stmt = $mysqli->prepare('INSERT INTO user (wechat_name, avatar, openid, create_time) VALUES (?, ?, ?, NOW())');
$stmt->bind_param('sss', $nickName, $avatarUrl, $openid);
$stmt->execute();
$id = $stmt->insert_id;
$user = ['id' => $id, 'wechat_name' => $nickName, 'avatar' => $avatarUrl, 'openid' => $openid, 'create_time' => date('Y-m-d H:i:s')];
jsonResponse(0, $user, '注册成功');
?>