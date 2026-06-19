<?php
header('Content-Type: application/json; charset=utf-8');
$host = '127.0.0.1';
$user = 'root';
$pass = '';
$dbname = 'mini_program';
$mysqli = new mysqli($host, $user, $pass, $dbname);
if ($mysqli->connect_errno) {
    echo json_encode(['code' => 1, 'message' => '数据库连接失败']);
    exit;
}
$mysqli->set_charset('utf8mb4');
function jsonResponse($code, $data = null, $message = '') {
    echo json_encode(['code' => $code, 'data' => $data, 'message' => $message]);
    exit;
}
?>