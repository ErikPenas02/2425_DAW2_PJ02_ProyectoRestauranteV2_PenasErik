<?php
session_start();
require_once "../Procesos/conection.php";


if(isset($_SESSION["rolAct"]) && $_SESSION["rolAct"] !== 1){
    header("Location: ./inicio.php?rolAct=denied");
    exit();
}

if(!isset($_GET["crear"]) && isset($_GET["crear"]) === "si"){
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

if(isset($_GET["crear"])){
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
?>
    
</body>
</html>