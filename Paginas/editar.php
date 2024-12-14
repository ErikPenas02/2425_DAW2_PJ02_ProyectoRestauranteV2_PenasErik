<?php
session_start();
require_once "../Procesos/conection.php";


if(isset($_SESSION["rolAct"]) && $_SESSION["rolAct"] !== 1){
    header("Location: ./inicio.php?rolAct=denied");
    exit();
}

if(!isset($_GET["edit_user"]) && !isset($_GET["del_user"])){
    header("Location: ./users.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../CSS/estilos-asignar.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="../JS/validaciones.js"></script>
    <!-- </title>Document</title> -->
</head>
<body>
<?php
if(isset($_GET["del_user"])){
    $id_del = htmlspecialchars($_GET["del_user"]);
    try{
        $pdo->beginTransaction();

        $sqlUpdateReserva = "UPDATE tbl_historial SET asignado_por = :id_yo WHERE asignado_por = :id_user;";
        $stmt = $pdo->prepare($sqlUpdateReserva);
        $stmt->bindParam(':id_yo', $_SESSION["usuarioAct"]);
        $stmt->bindParam(':id_user', $id_del);
        $stmt->execute();

        $sqlDel = "DELETE FROM tbl_usuarios WHERE id_usuario = :id_user;";
        $stmt2 = $pdo->prepare($sqlDel);
        $stmt2->bindParam(':id_user', $id_del);
        $stmt2->execute();

        $pdo->commit();
        header("Location: ./users.php?exito=delete");
        exit();
    } catch (PDOException $e) {
        $pdo->rollBack();
        echo "Error al encontrar datos del usuario: " . $e->getMessage();
        die();
    }
}

if(isset($_GET["edit_user"])){
    $id_edit = htmlspecialchars($_GET["edit_user"]);
    try{
        $sqlUser = "SELECT u.*, r.* FROM tbl_usuarios u INNER JOIN tbl_roles r ON r.id_rol = u.id_rol WHERE id_usuario = :id_user;";
        $stmt = $pdo->prepare($sqlUser);
        $stmt->bindParam(':id_user', $id_edit);
        $stmt->execute();
        $editar = $stmt->fetch(PDO::FETCH_ASSOC);
        
        $sqlRoles = "SELECT * FROM tbl_roles;";
        $stmt = $pdo->prepare($sqlRoles);
        $stmt->execute();
        $roles = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "Error al encontrar datos del usuario: " . $e->getMessage();
        die();
    }
    ?>
    <div class="container mt-5">
        <h2 class="text-center mb-4">Editar Usuario</h2>
        <form action="../Procesos/procesoCrud.php" method="POST" class="p-4 border rounded bg-light">
            <div class="row">
                <!-- Campo para el Nombre -->
                <div class="col-md-6 mb-3">
                    <label for="nombre" class="form-label">Nombre</label>
                    <input type="text" class="form-control" id="nombre" name="nombre" value="<?php echo htmlspecialchars($editar['nombre_usuario']); ?>">
                    <span style="color: red;" id="error-nombre"></span>
                </div>
                <!-- Campo para el Apellido -->
                <div class="col-md-6 mb-3">
                    <label for="apellido" class="form-label">Apellido</label>
                    <input type="text" class="form-control" id="apellido" name="apellido" value="<?php echo htmlspecialchars($editar['apellido_usuario']); ?>">
                    <span style="color: red;" id="error-ap"></span>
                </div>
            </div>

            <div class="row">
                <!-- Campo para el Username -->
                <div class="col-md-6 mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" class="form-control" id="username" name="username" value="<?php echo htmlspecialchars($editar['username']); ?>">
                    <span style="color: red;" id="error-username"></span>
                </div>
                <!-- Campo para el Rol -->
                <div class="col-md-6 mb-3">
                    <label for="rol" class="form-label">Rol</label>
                    <select class="form-select" id="rol" name="rol">
                        <?php
                        foreach ($roles as $rol) {
                            echo '<option value="' . $rol["id_rol"] . '"' . 
                            ($rol["id_rol"] === $editar["id_rol"] ? ' selected' : '') . '>' . 
                            htmlspecialchars($rol["nombre_rol"]) . '</option>';
                        }
                        ?>
                    </select>
                </div>
            </div>
            
            <!-- Campo oculto para enviar el ID del usuario -->
            <input type="hidden" name="id_usuario" value="<?php echo $editar['id_usuario']; ?>">

            <div class="text-center">
                <button type="submit" name="edit_user" class="btn btn-primary">Guardar Cambios</button>
                <a href="./users.php"><button type="button" class="btn btn-outline-danger">Cancelar</button></a>
            </div>
        </form>
    </div>

<?php
}
?>
    
</body>
</html>