<?php
session_start();
require_once "../Procesos/conection.php";

if (isset($_SESSION["rolAct"]) && $_SESSION["rolAct"] != 1) {
    header('Location: ./inicio.php');
    exit();
}

// Filtrado para salas
$filtrosSalas = [];
$paramsSalas = [];

if (!empty($_GET['search_sala'])) {
    $searchSala = htmlspecialchars($_GET['search_sala']);
    $filtrosSalas[] = "nombre_sala LIKE :search_sala";
    $paramsSalas['search_sala'] = "%$searchSala%";
}

if (!empty($_GET['tipo_sala'])) {
    $tipoSala = htmlspecialchars($_GET['tipo_sala']);
    $filtrosSalas[] = "tipo_sala = :tipo_sala";
    $paramsSalas['tipo_sala'] = $tipoSala;
}

// Filtrado para recursos
$filtrosRecursos = [];
$paramsRecursos = [];

if (!empty($_GET['search_recurso'])) {
    $searchRecurso = htmlspecialchars($_GET['search_recurso']);
    $filtrosRecursos[] = "nombre_recurso LIKE :search_recurso";
    $paramsRecursos['search_recurso'] = "%$searchRecurso%";
}

if (!empty($_GET['tipo_recurso'])) {
    $tipoRecurso = htmlspecialchars($_GET['tipo_recurso']);
    $filtrosRecursos[] = "tipo_recurso = :tipo_recurso";
    $paramsRecursos['tipo_recurso'] = $tipoRecurso;
}

try {
    // Consultar salas
    $whereSQLSalas = count($filtrosSalas) > 0 ? "WHERE " . implode(" AND ", $filtrosSalas) : "";
    $sqlSalas = "SELECT * FROM tbl_salas $whereSQLSalas";
    $stmtSalas = $pdo->prepare($sqlSalas);
    $stmtSalas->execute($paramsSalas);
    $salas = $stmtSalas->fetchAll(PDO::FETCH_ASSOC);

    // Consultar recursos
    $whereSQLRecursos = count($filtrosRecursos) > 0 ? "WHERE " . implode(" AND ", $filtrosRecursos) : "";
    $sqlRecursos = "SELECT r.*, s.nombre_sala, r2.* FROM tbl_recursos r 
                    INNER JOIN tbl_recursos r2 ON r.id_recurso = r2.id_recurso
                    LEFT JOIN tbl_salas s ON r.id_sala = s.id_sala $whereSQLRecursos";
    $stmtRecursos = $pdo->prepare($sqlRecursos);
    $stmtRecursos->execute($paramsRecursos);
    $recursos = $stmtRecursos->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error al filtrar: " . $e->getMessage();
    die();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Salas y Recursos</title>
    <link rel="stylesheet" href="../CSS/estilos-crud.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="../JS/alertAsignar.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container my-4">
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

    <h1 class="text-center">Gestión de Salas</h1>
    <form method="GET" class="row mb-3">
        <div class="col-md-6">
            <input type="text" name="search_sala" class="form-control" placeholder="Buscar por nombre de sala" value="<?php echo htmlspecialchars($_GET['search_sala'] ?? ''); ?>">
        </div>
        <div class="col-md-4">
            <select name="tipo_sala" class="form-select">
                <option value="">Filtrar por tipo</option>
                <option value="Comedor" <?php echo (isset($_GET['tipo_sala']) && $_GET['tipo_sala'] == 'Comedor') ? 'selected' : ''; ?>>Comedor</option>
                <option value="Terraza" <?php echo (isset($_GET['tipo_sala']) && $_GET['tipo_sala'] == 'Terraza') ? 'selected' : ''; ?>>Terraza</option>
                <option value="VIP" <?php echo (isset($_GET['tipo_sala']) && $_GET['tipo_sala'] == 'VIP') ? 'selected' : ''; ?>>VIP</option>
            </select>
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-primary w-100">Buscar</button>
        </div>
    </form>
    <div class="table-responsive-scroll">
        <table class="table table-bordered table-hover">
            <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Tipo</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($salas as $sala) { ?>
                <tr>
                    <td><?php echo $sala['id_sala']; ?></td>
                    <td><?php echo htmlspecialchars($sala['nombre_sala']); ?></td>
                    <td><?php echo htmlspecialchars($sala['tipo_sala']); ?></td>
                    <td>
                        <a href="./editar.php?edit_sala=<?php echo $sala['id_sala']?>">
                            <button type="button" class="btn btn-success">Editar</button>
                        </a>
                        <a href="./editar.php?del_sala=<?php echo $sala['id_sala']?>">
                            <button type="button" class="btn btn-danger">Eliminar</button>
                        </a>
                    </td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
    </div>
        <a href="../Paginas/crear.php?crear=salas">
            <button type="button" class="btn btn-secondary">CREAR</button>
        </a>
    <br>
    <h1 class="text-center">Gestión de Recursos</h1>
    <form method="GET" class="row mb-3">
        <div class="col-md-6">
            <input type="text" name="search_recurso" class="form-control" placeholder="Buscar por nombre de recurso" value="<?php echo htmlspecialchars($_GET['search_recurso'] ?? ''); ?>">
        </div>
        <div class="col-md-4">
            <select name="tipo_recurso" class="form-select">
                <option value="">Filtrar por tipo</option>
                <option value="Mesa" <?php echo (isset($_GET['tipo_recurso']) && $_GET['tipo_recurso'] == 'Mesa') ? 'selected' : ''; ?>>Mesa</option>
                <option value="Silla" <?php echo (isset($_GET['tipo_recurso']) && $_GET['tipo_recurso'] == 'Silla') ? 'selected' : ''; ?>>Silla</option>
            </select>
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-primary w-100">Buscar</button>
        </div>
    </form>
    <div class="table-responsive-scroll">
        <table class="table table-bordered table-hover">
            <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Tipo</th>
                    <th>Sala</th>
                    <th>Recurso Padre</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($recursos as $recurso) { ?>
                <tr>
                    <td><?php echo $recurso['id_recurso']; ?></td>
                    <td><?php echo htmlspecialchars($recurso['nombre_recurso']); ?></td>
                    <td><?php echo htmlspecialchars($recurso['tipo_recurso']); ?></td>
                    <td><?php echo htmlspecialchars($recurso['nombre_sala'] ?? 'Sin Sala'); ?></td>
                    <td>
                        <?php 
                        if(!is_null($recurso['id_padre'])){
                            echo htmlspecialchars($recurso['id_padre']);
                        } else{
                            echo htmlspecialchars($recurso['nombre_sala'] ?? 'Sin Sala');
                        }
                        ; 
                        ?>
                    </td>
                    <td>
                        <a href="./editar.php?edit_recurso=<?php echo $recurso['id_recurso']?>">
                            <button type="button" class="btn btn-success">Editar</button>
                        </a>
                        <a href="./editar.php?del_recurso=<?php echo $recurso['id_recurso']?>">
                            <button type="button" class="btn btn-danger">Eliminar</button>
                        </a>
                    </td>
                </tr>
            <?php } ?>
            </tbody>
        </table> 
    </div>
    <a href="../Paginas/crear.php?crear=recursos">
        <button type="button" class="btn btn-secondary">CREAR</button>
    </a>
</div>
</body>
</html>
