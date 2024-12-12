<?php
    require_once "../Procesos/conection.php";
    session_start();

    // Comprobación de sesión activa
    if (!isset($_SESSION["usuarioAct"])) {
        header('Location: ../index.php');
        exit();
    }

    
    if (isset($_GET['id_sala'])) {
        $id_sala = $_GET['id_sala']; 
    
        // Sanitizar el nombre de la sala
        $id_sala = htmlspecialchars($id_sala);

        //Para facilitar la obtención del nombre de la Sala, haré 2 consultas
        try {
            // Consultar las mesas de la sala seleccionada
            $sqlMesas = "SELECT id_recurso, nombre_recurso FROM tbl_recursos WHERE id_sala = :id_sala
                         AND id_padre IS NULL";
            $stmt = $pdo->prepare($sqlMesas);
            $stmt->bindParam(':id_sala', $id_sala);
            $stmt->execute();
            $mesas = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            $sqlSillas = "SELECT id_recurso, nombre_recurso FROM tbl_recursos WHERE id_padre = :id_padre";
            $sqlNombreSala = "SELECT nombre_sala FROM tbl_salas WHERE id_sala = :id_sala";
            $stmt2 = $pdo->prepare($sqlNombreSala);
            $stmt2->bindParam(':id_sala', $id_sala);
            $stmt2->execute();
            $n_sala = $stmt2->fetch(PDO::FETCH_ASSOC);

            // Obtener la fecha y hora actual
            $ahora = date("Y-m-d H:i:s");
        
        } catch (PDOException $e) {
            echo "Error al cargar las mesas: " . $e->getMessage();
            die();
        }
    }  
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $n_sala["nombre_sala"]; ?></title>
    <link rel="stylesheet" href="../CSS/estilosInicio.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
    <!-- Navbar ya implementado -->
    <header class="p-2 bg-dark text-white">
        <div class="container">
            <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start">
                <ul class="nav col-12 col-lg-auto me-lg-auto mb-2 justify-content-center mb-md-0">
                    <li><a href="" class="nav-link px-2 text-secondary">Home</a></li>
                    <li><a href="./historial.php" class="nav-link px-2 text-white">Historial</a></li>
                </ul>
                <div class="text-end">
                    <a href="./salas.php"><button type="button" class="btn btn-outline-danger">Volver a Salas</button></a>
                    <a href="../Procesos/destruir.php"><button type="button" class="btn btn-outline-danger">Log Out</button></a>
                </div>
            </div>
        </div>
    </header>
    <div class="container mt-4">
    <div class="row">
        <h2>Mesas en <?php echo htmlspecialchars($n_sala["nombre_sala"]); ?></h2>
        <?php
        if (count($mesas) > 0) {
            foreach ($mesas as $mesa) {
                // Verificar si la mesa está asignada
                $sqlSillas = "SELECT COUNT(*) FROM tbl_recursos WHERE id_padre = :id_padre";
                $stmt3 = $pdo->prepare($sqlSillas);
                $stmt3->bindParam(':id_padre', $mesa["id_recurso"]);
                $stmt3->execute();
                $sillas = $stmt3->fetchColumn();

                $sqlAsignada = "SELECT COUNT(*) FROM tbl_historial WHERE id_recurso = :id_recurso 
                                AND :ahora BETWEEN fecha_asignacion AND fecha_no_asignacion";
                $stmtAsignada = $pdo->prepare($sqlAsignada);
                $stmtAsignada->bindParam(':id_recurso', $mesa['id_recurso']);
                $stmtAsignada->bindParam(':ahora', $ahora);
                $stmtAsignada->execute();
                $asignada = $stmtAsignada->fetchColumn() > 0;

                echo "<div class='col-12 col-sm-6 col-md-4 col-lg-3 mb-4'>
                <div class='mesa-card'>
                    <h3>" . htmlspecialchars($mesa['nombre_recurso']) . "</h3>
                    <p>Asientos: " . $sillas . "</p>" . ($asignada ? "<span class='asignada'>Asignada</span>" : "<span class='libre'>Libre</span>") . "</button></a></p>
                    <a href='./asignar_mesa.php?id_mesa=" . $mesa['id_recurso'] . "'><button type='button' class='btn btn-warning'>Reservar</button></a></p>
                </div>
                </div>";
            }
        } else {
            echo "<p>No hay mesas registradas en esta sala.</p>";
        }
        ?>
    </div>
    </div>
</body>
</html>
