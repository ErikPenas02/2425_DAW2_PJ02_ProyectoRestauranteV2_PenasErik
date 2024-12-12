<?php
session_start();

if(!isset($_SESSION["usuarioAct"])){
    header("Location: ../index.php");
    exit();
}

require_once "../Procesos/conection.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../CSS/estilosInicio.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <title>Document</title>
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
                    <a href="./inicio.php"><button type="button" class="btn btn-outline-danger">Volver a Inicio</button></a>
                    <a href="../Procesos/destruir.php"><button type="button" class="btn btn-outline-danger">Log Out</button></a>
                </div>
            </div>
        </div>

    </header>
    <div class="container mt-4">
    <div class="row">
        <?php
        try {
            $sqlInicio = "SELECT id_sala, nombre_sala, tipo_sala FROM tbl_salas";
            $stmt = $pdo->prepare($sqlInicio);
            $stmt->execute();
            $salas = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Renderizamos las salas como tarjetas
            foreach ($salas as $sala) {
                echo '<div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-4">
                    <div class="sala-card">
                        <h3><strong>' . htmlspecialchars($sala["nombre_sala"]) . '</strong></h3>
                        <p>Tipo: ' . htmlspecialchars($sala["tipo_sala"]) . '</p>
                        <a href="./mesas.php?id_sala=' . htmlspecialchars($sala["id_sala"]) . '" class="btn btn-primary">Ver Mesas</a>
                    </div>
                </div>';
            }
        } catch (PDOException $e) { 
            echo "Error al cargar salas: " . $e->getMessage();
            die();
        }
        ?>
    </div>
</div>

</body>
</html>