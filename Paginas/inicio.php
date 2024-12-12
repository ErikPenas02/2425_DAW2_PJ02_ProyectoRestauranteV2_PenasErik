<?php
session_start();

if(!isset($_SESSION["usuarioAct"])){
    header("Location: ../index.php");
    exit();
}

include_once "../Procesos/conection.php";
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TPV Salas</title>
    <link rel="stylesheet" href="../CSS/estilosInicio.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
    <header class="p-2 bg-dark text-white">
        <div class="container">
            <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start">
                <ul class="nav col-12 col-lg-auto me-lg-auto mb-2 justify-content-center mb-md-0">
                    <li><a href="" class="nav-link px-2 text-secondary">Home</a></li>
                    <li><a href="./historial.php" class="nav-link px-2 text-white">Historial</a></li>
                </ul>
                <div class="text-end">
                    <a href="../Procesos/destruir.php"><button type="button" class="btn btn-outline-danger">Log Out</button></a>
                </div>
            </div>
        </div>
    </header>

    <!-- Opciones de la página -->
    <div class="opciones">
        <a href="./salas.php" style="text-decoration: none;">
            <div class="opcion" id="opcionSalas">
                <span>SALAS</span>
            </div>
        </a>
    <?php
    if ($_SESSION["rolAct"] === 1) {
        ?>
        <a href="./historial.php" style="text-decoration: none;">
            <div class="opcion" id="opcionUsers">
                <span>USERS</span>
            </div>
        </a>
        
        <a href="./historial.php" style="text-decoration: none;">
            <div class="opcion" id="opcionRecursos">
                <span>RECURSOS</span>
            </div>
        </a>
    </div>
        <?php
    } else {
        echo "</div>";
        echo "<div class='mensajeInicio'>
                <p>Solo los Administradores de la Empresa pueden acceder a USERS y RECURSOS</p>
              </div>";
    }
    ?> 
    <!-- SweetAlert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="../JS/alertIndex.js"></script>
</body>
</html>