<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Asignar Mesa</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../CSS/estilos-asignar.css">
    <script src="../JS/validaciones.js"></script>
    <script src="../JS/alertAsignar.js"></script>
</head>
<body>
    <div class="container text-center mt-4">
        <?php
            require_once "../Procesos/conection.php";
            session_start();

            if(isset($_GET["del_reserva"])){
                try {
                    $sqlDelete = "DELETE FROM tbl_historial WHERE id_recurso = :id_mesa AND fecha_asignacion = :fch_asig";
                    $stmt = $pdo->prepare($sqlDelete);
                    $stmt->bindParam(':id_mesa', $id_mesa);
                    $stmt->bindParam(':fch_asig', $_GET["del_reserva"]);
                    $stmt->execute();
                    header("Location: ./asignar_mesa.php");
                    exit();

                } catch (PDOException $e) {
                    echo "<p>Error: " . htmlspecialchars($e->getMessage()) . "</p>";
                }

            }
            // Verificar si hay sesión activa
            if (!isset($_SESSION["usuarioAct"])) {
                header('Location: ../index.php');
                exit();
            }

            if (isset($_GET["id_mesa"])) {
                $_SESSION["id_mesa"] = htmlspecialchars($_GET["id_mesa"]);
            } 

            if (!isset($_SESSION["id_mesa"])) {
                header("Location: ../Paginas/salas.php");
                exit();
            }

            $id_mesa = htmlspecialchars($_SESSION["id_mesa"]);
            $hoy = date("Y-m-d H:i:s");
            // Mostrar el botón de regreso a la lista de mesas
            echo "<a href='./mesas.php'><button class='btn btn-secondary mb-4'>Volver a mesas</button></a>";
            echo "<h2>Asignar Recurso $id_mesa</h2>";

            // Si la mesa ya está asignada, muestra detalles
            try {
                $sqlReservas = "SELECT fecha_asignacion, fecha_no_asignacion FROM tbl_historial WHERE id_recurso = :id_mesa";
                $stmtReservas = $pdo->prepare($sqlReservas);
                $stmtReservas->bindParam(':id_mesa', $id_mesa);
                $stmtReservas->execute();
                $reservas = $stmtReservas->fetchAll(PDO::FETCH_ASSOC);
                
                echo "<form method='POST' id='form-asignar' action='../Procesos/procesoAsignar.php' class='text-left'>";
                echo "<div class='form-group'>";
                echo "<label for='assigned_to'>Asignar a:</label>";
                echo "<input type='text' id='assigned_to' name='assigned_to' class='form-control'>";
                echo "<span style='color: red;' id='errorAssignedTo'></span>";
                echo "</div>";
                echo "<div class='form-group'>";
                echo "<label for='fecha_inicio'>Fecha y Hora de Inicio:</label>";
                echo "<input type='datetime-local' id='fecha_inicio' name='fecha_inicio' class='form-control'>";
                echo "<span style='color: red;' id='errorFechaInicio'></span>";
                echo "</div>";
                echo "<div class='form-group'>";
                echo "<label for='fecha_fin'>Fecha y Hora de Fin:</label>";
                echo "<input type='datetime-local' id='fecha_fin' name='fecha_fin' class='form-control'>";
                echo "<span style='color: red;' id='errorFechaFin'></span>";
                echo "<input type='hidden' name='mesa' value='$id_mesa'>";
                echo "</div>";
                echo "<button type='submit' class='btn btn-success mt-3' name='formAsignar'>Asignar Mesa</button>";
                echo "</form>";
                if ($reservas) {
                    echo "<form method='GET' action='../asignar_mesa.php'>";
                    echo "<h5>Reservas existentes:</h5>";
                    foreach ($reservas as $reserva) {
                        if(is_null($reserva['fecha_no_asignacion'])){
                            $fecha_final = "";
                        } else {
                            $fecha_final = htmlspecialchars($reserva['fecha_no_asignacion']);
                        }
                        if ($hoy < $reserva['fecha_no_asignacion']){
                            echo htmlspecialchars($reserva['fecha_asignacion']) . "<-->" . $fecha_final . " " . 
                            "<input type='hidden' name='del_reserva' value='" . $reserva['fecha_asignacion'] . "'>" .
                            "<button type='button' class='btn btn-danger'>Eliminar Reserva</button></a>";
                        }
                        echo "</form>";
                    }
                }
            } catch (PDOException $e) {
                echo "<p>Error: " . htmlspecialchars($e->getMessage()) . "</p>";
            }
        ?>
    </div>
    <!-- Bootstrap -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.6.0/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>
</html>
