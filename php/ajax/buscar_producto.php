<?php
require_once '../database.php';

header('Content-Type: application/json; charset=utf-8');

$q = '';
if(isset($_GET['q'])){
	$q = trim($_GET['q']);
}

$database = new Database();
$db = $database->getConnection();

if($q === ''){
	// devolver últimos 10
	$stmt = $db->prepare('SELECT id, titulo, descripcion, imagen FROM productos ORDER BY fecha_creacion DESC LIMIT 10');
	$stmt->execute();
	$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
	echo json_encode(['success' => true, 'data' => $rows]);
	exit;
}

$stmt = $db->prepare('SELECT id, titulo, descripcion, imagen FROM productos WHERE titulo LIKE ? OR descripcion LIKE ? LIMIT 50');
$like = "%" . $q . "%";
$stmt->execute([$like, $like]);
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode(['success' => true, 'data' => $rows]);
exit;
?>
