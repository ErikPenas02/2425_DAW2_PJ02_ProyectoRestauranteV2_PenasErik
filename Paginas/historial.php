<?php
// Conexión PDO
$host = 'localhost';
$dbname = 'db_restaurante_v2';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("ERROR: No se pudo conectar. " . $e->getMessage());
}

// Variables para los filtros
$filtroAsignado = isset($_GET['filtroAsignado']) ? trim($_GET['filtroAsignado']) : '';
$filtroFechaInicio = isset($_GET['fechaInicio']) ? trim($_GET['fechaInicio']) : '';
$filtroFechaFin = isset($_GET['fechaFin']) ? trim($_GET['fechaFin']) : '';
$filtroRecurso = isset($_GET['filtroRecurso']) ? trim($_GET['filtroRecurso']) : '';

// Construcción de la consulta dinámica
$filtros = [];
$params = [];

if (!empty($filtroAsignado)) {
    $filtros[] = "(u.nombre_usuario LIKE :filtroAsignado OR h.asignado_a LIKE :filtroAsignado)";
    $params[':filtroAsignado'] = "%$filtroAsignado%";
}

if (!empty($filtroFechaInicio) && !empty($filtroFechaFin)) {
    $filtros[] = "(h.fecha_asignacion >= :fechaInicio AND h.fecha_no_asignacion <= :fechaFin)";
    $params[':fechaInicio'] = $filtroFechaInicio;
    $params[':fechaFin'] = $filtroFechaFin;
}

if (!empty($filtroRecurso)) {
    $filtros[] = "r.nombre_recurso LIKE :filtroRecurso";
    $params[':filtroRecurso'] = "%$filtroRecurso%";
}

$whereSQL = count($filtros) > 0 ? "WHERE " . implode(" AND ", $filtros) : "";
$sql = "
    SELECT h.id_historial, h.fecha_asignacion, h.fecha_no_asignacion, h.asignado_a, 
           r.nombre_recurso, u.nombre_usuario, u.apellido_usuario
    FROM tbl_historial h
    JOIN tbl_recursos r ON h.id_recurso = r.id_recurso
    JOIN tbl_usuarios u ON h.asignado_por = u.id_usuario
    $whereSQL
";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$historial = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historial</title>
    <link rel="stylesheet" href="../CSS/estilos-historial.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="../JS/alertAsignar.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<header class="p-2 bg-dark text-white">
        <div class="container">
            <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start">
                <ul class="nav col-12 col-lg-auto me-lg-auto mb-2 justify-content-center mb-md-0">
                    <li><a href="./inicio.php" class="nav-link px-2 text-secondary">Home</a></li>
                    <li><a href="./historial.php" class="nav-link px-2 text-white">Historial</a></li>
                </ul>
                <div class="text-end">
                    <a href="../Procesos/destruir.php"><button type="button" class="btn btn-outline-danger">Log Out</button></a>
                </div>
            </div>
        </div>
    </header>
    <h1>Filtros de Historial</h1>
    <form method="GET" action="" style="align-items: center; justify-content: center;">
        <label for="filtroAsignado">Asignado por o Asignado a:</label>
        <input type="text" name="filtroAsignado" id="filtroAsignado" value="<?= htmlspecialchars($filtroAsignado) ?>"><br>

        <label for="fechaInicio">Fecha de Asignación (desde):</label>
        <input type="datetime-local" name="fechaInicio" id="fechaInicio" value="<?= htmlspecialchars($filtroFechaInicio) ?>">

        <label for="fechaFin">Fecha de No Asignación (hasta):</label>
        <input type="datetime-local" name="fechaFin" id="fechaFin" value="<?= htmlspecialchars($filtroFechaFin) ?>"><br>

        <label for="filtroRecurso">Nombre del Recurso:</label>
        <input type="text" name="filtroRecurso" id="filtroRecurso" value="<?= htmlspecialchars($filtroRecurso) ?>"><br>

        <button type="submit" class="btn btn-outline-primary">Filtrar</button>
    </form>

    <h2>Resultados</h2>
    <?php if (count($historial) > 0): ?>
        <table>
            <tr>
                <th>ID</th>
                <th>Fecha de Asignación</th>
                <th>Fecha de Desasignación</th>
                <th>Asignado a</th>
                <th>Recurso</th>
                <th>Asignado por</th>
            </tr>
            <?php foreach ($historial as $row): ?>
                <tr>
                    <td><?= htmlspecialchars($row['id_historial']) ?></td>
                    <td><?= htmlspecialchars($row['fecha_asignacion']) ?></td>
                    <td><?= htmlspecialchars($row['fecha_no_asignacion'] ?: 'N/A') ?></td>
                    <td><?= htmlspecialchars($row['asignado_a']) ?></td>
                    <td><?= htmlspecialchars($row['nombre_recurso']) ?></td>
                    <td><?= htmlspecialchars($row['nombre_usuario'] . ' ' . $row['apellido_usuario']) ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php else: ?>
        <p>No se encontraron resultados con los filtros aplicados.</p>
    <?php endif; ?>
</body>
</html>
