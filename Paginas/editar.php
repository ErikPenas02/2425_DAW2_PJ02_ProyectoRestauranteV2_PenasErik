<?php
session_start();
require_once "../Procesos/conection.php";


if(isset($_SESSION["rolAct"]) && $_SESSION["rolAct"] !== 1){
    header("Location: ./inicio.php?rolAct=denied");
    exit();
}

if(!isset($_GET["edit_user"]) && !isset($_GET["del_user"]) &&
    !isset($_GET["edit_sala"]) && !isset($_GET["del_sala"]) && 
    !isset($_GET["edit_recurso"]) && !isset($_GET["del_recurso"])){
        header("Location: ./inicio.php");
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
<?php
if(isset($_GET["edit_sala"])){
    $id_edit = htmlspecialchars($_GET["edit_sala"]);
    try {
        // Consultar datos de la sala
        $sqlSala = "SELECT * FROM tbl_salas WHERE id_sala = :id_sala";
        $stmt = $pdo->prepare($sqlSala);
        $stmt->bindParam(':id_sala', $id_edit, PDO::PARAM_INT);
        $stmt->execute();
        $editar = $stmt->fetch(PDO::FETCH_ASSOC);

        // Obtener salas para la lista de selección
        $sqlSalas = "SELECT * FROM tbl_salas";
        $stmt = $pdo->prepare($sqlSalas);
        $stmt->execute();
        $salas = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "Error al cargar datos: " . $e->getMessage();
        die();
    }
    ?>
    <div class="container mt-5">
        <h2 class="text-center mb-4">Editar <?php echo htmlspecialchars($editar['nombre_sala']); ?></h2>
        <form action="../Procesos/procesoCrud.php" method="POST" class="p-4 border rounded bg-light">
            <div class="mb-3">
                <label for="nombre_sala" class="form-label">Nombre de la Sala</label>
                <input type="text" class="form-control" id="nombre_sala" name="nombre_sala"
                    value="<?php echo htmlspecialchars($editar['nombre_sala']); ?>" >
            </div>
            <div class="mb-3">
                <label for="tipo_sala" class="form-label">Tipo de Sala</label>
                <select class="form-select" id="tipo_sala" name="tipo_sala">
                    <option value="Comedor" <?php echo $editar['tipo_sala'] === "Comedor" ? "selected" : ""; ?>>Comedor</option>
                    <option value="Terraza" <?php echo $editar['tipo_sala'] === "Terraza" ? "selected" : ""; ?>>Terraza</option>
                    <option value="VIP" <?php echo $editar['tipo_sala'] === "VIP" ? "selected" : ""; ?>>VIP</option>
                </select>
            </div>
            <input type="hidden" name="id_sala" value="<?php echo $editar['id_sala']; ?>">
            <div class="text-center">
                <button type="submit" name="edit_sala" class="btn btn-primary">Guardar Cambios</button>
                <a href="./recursos.php"><button type="button" class="btn btn-outline-danger">Cancelar</button></a>
            </div>
        </form>
    </div>
    
<?php
}

?>
<?php
if(isset($_GET["edit_recurso"])){
    $id_edit = htmlspecialchars($_GET["edit_sala"]);
    try {
        // Consultar datos del recurso
        $sqlRecurso = "SELECT * FROM tbl_recursos WHERE id_recurso = :id_recurso";
        $stmt = $pdo->prepare($sqlRecurso);
        $stmt->bindParam(':id_recurso', $id_edit, PDO::PARAM_INT);
        $stmt->execute();
        $editar = $stmt->fetch(PDO::FETCH_ASSOC);

        // Obtener salas para la lista de selección
        $sqlSalas = "SELECT * FROM tbl_salas";
        $stmt = $pdo->prepare($sqlSalas);
        $stmt->execute();
        $salas = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "Error al cargar datos: " . $e->getMessage();
        die();
    }
    ?>
    <div class="container mt-5">
        <h2 class="text-center mb-4">Editar <?php echo htmlspecialchars($editar['nombre_recurso']);?></h2>
        <form action="../Procesos/procesoCrud.php" method="POST" class="p-4 border rounded bg-light">
            <div class="mb-3">
                <label for="nombre_recurso" class="form-label">Nombre del Recurso</label>
                <input type="text" class="form-control" id="nombre_recurso" name="nombre_recurso"
                       value="<?php echo htmlspecialchars($editar['nombre_recurso']); ?>" >
            </div>
            <div class="mb-3">
                <label for="tipo_recurso" class="form-label">Tipo de Recurso</label>
                <select class="form-select" id="tipo_recurso" name="tipo_recurso" >
                    <option value="Mesa" <?php echo $editar['tipo_recurso'] === "Mesa" ? "selected" : ""; ?>>Mesa</option>
                    <option value="Silla" <?php echo $editar['tipo_recurso'] === "Silla" ? "selected" : ""; ?>>Silla</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="id_sala" class="form-label">Sala Asignada</label>
                <select class="form-select" id="id_sala" name="id_sala">
                    <option value="">Ninguna</option>
                    <?php foreach ($salas as $sala) { ?>
                        <option value="<?php echo $sala['id_sala']; ?>" 
                            <?php echo $editar['id_sala'] == $sala['id_sala'] ? "selected" : ""; ?>>
                            <?php echo htmlspecialchars($sala['nombre_sala']); ?>
                        </option>
                    <?php } ?>
                </select>
            </div>
            <input type="hidden" name="id_recurso" value="<?php echo $editar['id_recurso']; ?>">
            <div class="text-center">
                <button type="submit" name="edit_recurso" class="btn btn-primary">Guardar Cambios</button>
                <a href="./recursos.php"><button type="button" class="btn btn-outline-danger">Cancelar</button></a>
            </div>
        </form>
    </div>
<?php
}

if(isset($_GET["del_sala"])){
    $id_del = htmlspecialchars($_GET["del_sala"]);
    try{
        $pdo->beginTransaction();

        $sqlUpdateRecurso = "UPDATE tbl_recursos SET id_sala = NULL WHERE id_sala = :id_sala;";
        $stmt = $pdo->prepare($sqlUpdateRecurso);
        $stmt->bindParam(':id_sala', $id_del);
        $stmt->execute();

        $sqlDel = "DELETE FROM tbl_salas WHERE id_usuario = :id_sala;";
        $stmt2 = $pdo->prepare($sqlDel);
        $stmt2->bindParam(':id_user', $id_del);
        $stmt2->execute();

        $pdo->commit();
        header("Location: ./recursos.php?exito=delete_sala");
        exit();
    } catch (PDOException $e) {
        $pdo->rollBack();
        echo "Error al encontrar datos del usuario: " . $e->getMessage();
        die();
    }
}

if(isset($_GET["del_recurso"])){
    $id_del = htmlspecialchars($_GET["del_sala"]);
    try{
        $pdo->beginTransaction();

        $sqlUpdateRecurso = "UPDATE tbl_recursos SET id_padre = NULL WHERE id_padre = :id_recurso;";
        $stmt = $pdo->prepare($sqlUpdateRecurso);
        $stmt->bindParam(':id_recurso', $id_del);
        $stmt->execute();

        $sqlDel = "DELETE FROM tbl_recursos WHERE id_recurso = :id_recurso;";
        $stmt2 = $pdo->prepare($sqlDel);
        $stmt2->bindParam(':id_recurso', $id_del);
        $stmt2->execute();

        $pdo->commit();
        header("Location: ./recursos.php?exito=delete_recurso");
        exit();
    } catch (PDOException $e) {
        $pdo->rollBack();
        echo "Error al encontrar datos del usuario: " . $e->getMessage();
        die();
    }
}

?>

</body>
</html>