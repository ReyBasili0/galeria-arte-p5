<?php
session_start();
if(!isset($_SESSION['usuario_id'])){
    header("Location: login.html");
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Obra</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="../index.php">🎨 Volver a Galería</a>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header bg-success text-white">
                        <h4>➕ Agregar Nueva Obra</h4>
                    </div>
                    <div class="card-body">
                        <form action="../php/productos/agregar.php" method="POST">
                            <div class="mb-3">
                                <label class="form-label">Título *</label>
                                <input type="text" class="form-control" name="titulo" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Descripción</label>
                                <textarea class="form-control" name="descripcion" rows="3"></textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Categoría *</label>
                                <select class="form-select" name="categoria" required>
                                    <option value="digital">Arte Digital</option>
                                    <option value="tradicional">Tradicional</option>
                                    <option value="sketch">Sketch</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Técnica</label>
                                <input type="text" class="form-control" name="tecnica">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Precio (MXN)</label>
                                <input type="number" class="form-control" name="precio" min="0" step="0.01">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">URL de Imagen</label>
                                <input type="url" class="form-control" name="imagen" 
                                       placeholder="https://ejemplo.com/imagen.jpg">
                            </div>
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-success">Guardar Obra</button>
                                <a href="../index.php" class="btn btn-secondary">Cancelar</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>