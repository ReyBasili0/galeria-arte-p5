<?php
session_start();
require_once '../database.php';

if(!isset($_SESSION['usuario_id'])){
	header('Location: ../../forms/login.html');
	exit;
}

$database = new Database();
$db = $database->getConnection();

$stmt = $db->prepare('SELECT id, username, email, rol, fecha_registro FROM usuarios WHERE id = ?');
$stmt->execute([$_SESSION['usuario_id']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if(!$user){
	echo "Usuario no encontrado";
	exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Perfil - <?= htmlspecialchars($user['username']) ?></title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
	<div class="container mt-5">
		<div class="row justify-content-center">
			<div class="col-md-6">
				<div class="card">
					<div class="card-header bg-primary text-white">
						<h4>Perfil de Usuario</h4>
					</div>
					<div class="card-body">
						<p><strong>Usuario:</strong> <?= htmlspecialchars($user['username']) ?></p>
						<p><strong>Email:</strong> <?= htmlspecialchars($user['email']) ?></p>
						<p><strong>Rol:</strong> <?= htmlspecialchars($user['rol']) ?></p>
						<p><strong>Registrado:</strong> <?= htmlspecialchars($user['fecha_registro']) ?></p>

						<div class="d-grid gap-2">
							<a href="../../index.php" class="btn btn-secondary">← Volver al Inicio</a>
							<a href="../../forms/editar-obra.html" class="btn btn-warning">Mis Obras</a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</body>
</html>
<?php
exit;
?>
