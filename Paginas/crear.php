<?php
session_start();
require_once "../Procesos/conection.php";


if(isset($_SESSION["rolAct"]) && $_SESSION["rolAct"] !== 1){
    header("Location: ./inicio.php?rolAct=denied");
    exit();
}

if(!isset($_GET["crear"])){
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

if(isset($_GET["crear"]) && $_GET["crear"] === "users"){
    try{
        $sqlRoles = "SELECT * FROM tbl_roles";
        $stmt = $pdo->prepare($sqlRoles);
        $stmt->execute();
        $roles = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "Error al encontrar datos del usuario: " . $e->getMessage();
        die();
    }
    ?>
    <div class="container mt-5">
        <h2 class="text-center mb-4">Crear Usuario</h2>
        <form action="../Procesos/procesoCrud.php" method="POST" class="p-4 border rounded bg-light">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="nombre" class="form-label">Nombre</label>
                    <input type="text" class="form-control" id="nombre" name="nombre">
                    <span style="color: red;" id="error-nombre"></span>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="apellido" class="form-label">Apellido</label>
                    <input type="text" class="form-control" id="apellido" name="apellido">
                    <span style="color: red;" id="error-ap"></span>
                </div>
            </div>

            <div class="row">
                <!-- Campo para el Username -->
                <div class="col-md-6 mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" class="form-control" id="username" name="username">
                    <span style="color: red;" id="error-username"></span>
                </div>
                <!-- Campo para el Rol -->
                <div class="col-md-6 mb-3">
                    <label for="rol" class="form-label">Rol</label>
                    <select class="form-select" id="rol" name="rol">
                        <?php
                        foreach ($roles as $rol) {
                            echo '<option value="' . $rol["id_rol"] . '">' . 
                            htmlspecialchars($rol["nombre_rol"]) . '</option>';
                        }
                        ?>
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="nombre" class="form-label">Password</label>
                    <br>
                    <input type="password" class="form-control" id="pwd" name="pwd">
                    <span style="color: red;" id="error-pwd"></span>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="apellido" class="form-label">Repetir PWD</label>
                    <br>
                    <input type="password" class="form-control" id="repPwd">
                    <span style="color: red;" id="error-reppwd"></span>
                </div>
            </div>
            <div class="text-center">
                <button type="submit" name="crear_user" class="btn btn-primary">Guardar Cambios</button>
                <a href="./users.php"><button type="button" class="btn btn-outline-danger">Cancelar</button></a>
            </div>
        </form>
    </div>

<?php
}

if (isset($_GET["crear"]) && $_GET["crear"] === "salas") {
    ?>
    <div class="container mt-5">
        <h2 class="text-center mb-4">Crear Sala</h2>
        <form action="../Procesos/procesoCrud.php" method="POST" class="p-4 border rounded bg-light">
            <div class="mb-3">
                <label for="nombre_sala" class="form-label">Nombre de la Sala</label>
                <input type="text" class="form-control" id="nombre_sala" name="nombre_sala" required>
            </div>
            <div class="mb-3">
                <label for="tipo_sala" class="form-label">Tipo de Sala</label>
                <select class="form-select" id="tipo_sala" name="tipo_sala" required>
                    <option value="Comedor">Comedor</option>
                    <option value="Terraza">Terraza</option>
                    <option value="VIP">VIP</option>
                </select>
            </div>
            <div class="text-center">
                <button type="submit" name="crear_sala" class="btn btn-primary">Guardar Sala</button>
                <a href="./recursos.php"><button type="button" class="btn btn-outline-danger">Cancelar</button></a>
            </div>
        </form>
    </div>
    <?php
}

if (isset($_GET["crear"]) && $_GET["crear"] === "recursos") {
    try {
        $sqlSalas = "SELECT id_sala, nombre_sala FROM tbl_salas";
        $stmt = $pdo->prepare($sqlSalas);
        $stmt->execute();
        $salas = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $sqlPadres = "SELECT * FROM tbl_recursos WHERE id_padre IS NOT NULL";
        $stmt = $pdo->prepare($sqlPadres);
        $stmt->execute();
        $padres = $stmt->fetchAll(PDO::FETCH_ASSOC);

    } catch (PDOException $e) {
        echo "Error al encontrar datos de las salas: " . $e->getMessage();
        die();
    }
    ?>
    <div class="container mt-5">
        <h2 class="text-center mb-4">Crear Recurso</h2>
        <form action="../Procesos/procesoCrud.php" method="POST" class="p-4 border rounded bg-light">
            <div class="mb-3">
                <label for="nombre_recurso" class="form-label">Nombre del Recurso</label>
                <input type="text" class="form-control" id="nombre_recurso" name="nombre_recurso" required>
            </div>
            <div class="mb-3">
                <label for="tipo_recurso" class="form-label">Tipo de Recurso</label>
                <select class="form-select" id="tipo_recurso" name="tipo_recurso" required>
                    <option value="Mesa">Mesa</option>
                    <option value="Silla">Silla</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="id_sala" class="form-label">Asignar a Sala (opcional)</label>
                <select class="form-select" id="id_sala" name="id_sala">
                    <option value="">-- Ninguna --</option>
                    <?php
                    foreach ($salas as $sala) {
                        echo '<option value="' . $sala["id_sala"] . '">' . htmlspecialchars($sala["nombre_sala"]) . '</option>';
                    }
                    ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="id_padre" class="form-label">Asignar a Sala (opcional)</label>
                <select class="form-select" id="id_padre" name="id_padre">
                    <option value="">-- Ninguna --</option>
                    <?php
                    foreach ($padres as $padre) {
                        echo '<option value="' . $padre["id_recurso"] . '">' . htmlspecialchars($padre["nombre_recurso"]) . '</option>';
                    }
                    ?>
                </select>
            </div>
            <div class="text-center">
                <button type="submit" name="crear_recurso" class="btn btn-primary">Guardar Recurso</button>
                <a href="./recursos.php"><button type="button" class="btn btn-outline-danger">Cancelar</button></a>
            </div>
        </form>
    </div>
    <?php
}
?>
</body>
</html>