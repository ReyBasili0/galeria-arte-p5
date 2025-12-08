<?php
require_once '../database.php';

header('Content-Type: application/json; charset=utf-8');

if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['username'])){
	$database = new Database();
	$db = $database->getConnection();

	$username = trim($_POST['username']);
	$stmt = $db->prepare('SELECT id FROM usuarios WHERE username = ? OR email = ?');
	$stmt->execute([$username, $username]);

	echo json_encode(['exists' => $stmt->rowCount() > 0]);
	exit;
}

echo json_encode(['exists' => false]);
exit;
?>
