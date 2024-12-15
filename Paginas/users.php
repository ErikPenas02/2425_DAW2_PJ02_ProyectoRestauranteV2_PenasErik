<?php
session_start();
require_once "../Procesos/conection.php";


if(isset($_SESSION["rolAct"]) && $_SESSION["rolAct"] != 1){
    header('Location: ./inicio.php');
    exit();
}

// Filtrado de usuarios
$filtros = [];
$params = [];

if (!empty($_GET['search'])) {
    $search = htmlspecialchars($_GET['search']);
    $filtros[] = "(nombre_usuario LIKE :search OR username LIKE :search)";
    $params['search'] = "%$search%";
}

if (!empty($_GET['id_rol'])) {
    $id_rol = intval($_GET['id_rol']);
    $filtros[] = "u.id_rol = :id_rol";
    $params['id_rol'] = $id_rol;
}

try {
    // CONSULTA para los filtros
    $whereSQL = count($filtros) > 0 ? "WHERE " . implode(" AND ", $filtros) : "";
    $sql = "SELECT u.*, r.nombre_rol FROM tbl_usuarios u INNER JOIN tbl_roles r ON u.id_rol = r.id_rol $whereSQL";
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Obtener roles para el filtro del Select
    $sqlRoles = "SELECT * FROM tbl_roles";
    $stmtRoles = $pdo->query($sqlRoles);
    $roles = $stmtRoles->fetchAll(PDO::FETCH_ASSOC);
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
    <title>Gestión de Usuarios</title>
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
    <h1 class="text-center">Gestión de Usuarios</h1>

    <form method="GET" class="row mb-3">
        <div class="col-md-6">
            <input type="text" name="search" class="form-control" placeholder="Buscar por nombre o username" value="<?php echo htmlspecialchars($_GET['search'] ?? ''); ?>">
        </div>
        <div class="col-md-4">
            <select name="id_rol" class="form-select">
                <option value="">Filtrar por rol</option>
                <?php foreach ($roles as $rol): ?>
                    <option value="<?php echo $rol['id_rol']; ?>" <?php echo (isset($_GET['id_rol']) && $_GET['id_rol'] == $rol['id_rol']) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($rol['nombre_rol']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-primary w-100">Buscar</button>
        </div>
    </form>

    <table class="table table-bordered table-hover">
        <thead class="table-light">
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Apellido</th>
                <th>Username</th>
                <th>Rol</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($usuarios as $usuario) { ?>
            <tr>
                <td><?php echo $usuario['id_usuario']; ?></td>
                <td><?php echo htmlspecialchars($usuario['nombre_usuario']); ?></td>
                <td><?php echo htmlspecialchars($usuario['apellido_usuario']); ?></td>
                <td><?php echo htmlspecialchars($usuario['username']); ?></td>
                <td><?php echo htmlspecialchars($usuario['nombre_rol']); ?></td>
                <td><?php if($usuario['id_usuario'] != $_SESSION["usuarioAct"]){ ?>
                    <a href="./editar.php?edit_user=<?php echo $usuario['id_usuario']?>">
                        <button type="button" class="btn btn-success">Editar</button>
                    </a>
                    <a href="./editar.php?del_user=<?php echo $usuario['id_usuario']?>">
                        <button type="button" class="btn btn-danger">Eliminar</button>
                    </a>
                    <?php }?>
                </td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
    <a href="../Paginas/crear.php?crear=users">
        <button type="button" class="btn btn-secondary">CREAR</button>
    </a>
</body>
</html>
<?php
if (isset($_GET['exito'])) {
    switch ($_GET["exito"]) {
        case 'edit':
                echo "<script>
                    document.addEventListener('DOMContentLoaded', function () {
                        Exito('Bien Editado', 'El usuario ha sido modificado correctamente.');
                    });
                    </script>";
            break;
    
        case 'crear':
                echo "<script>
                    document.addEventListener('DOMContentLoaded', function () {
                        Exito('Bien Creado', 'El usuario ha sido creado correctamente.');
                    });
                </script>";
            break;
    
        case 'delete':
                echo "<script>
                    document.addEventListener('DOMContentLoaded', function () {
                        Exito('Eliminación Exitosa', 'El usuario ha sido eliminado correctamente.');
                    });
                </script>";
            break;
        
        default:
            # code...
            break;
    }
}
?>


