<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Asignar Mesa</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../CSS/estilos-asignar.css">
    <script src="../JS/validaciones.js"></script>
</head>
<body>
    <div class="container text-center mt-4">
        <?php
            require_once "../Procesos/conection.php";
            session_start();

            // Verificar si hay sesión activa
            if (!isset($_SESSION["usuarioAct"])) {
                header('Location: ../index.php');
                exit();
            }

            // Verificar si se recibió un id_mesa
            if (!isset($_GET["id_mesa"])) {
                header('Location: ./inicio.php');
                exit();
            }

            $id_mesa = htmlspecialchars($_GET["id_mesa"]);

            // Mostrar el botón de regreso a la lista de mesas
            echo "<a href='mesas.php'><button class='btn btn-secondary mb-4'>Volver a mesas</button></a>";
            echo "<h2>Asignar Recurso $id_mesa</h2>";

            // Si la mesa ya está asignada, muestra detalles
            try {
                $sqlCheckAsignacion = "SELECT * FROM tbl_historial WHERE id_recurso = :id_mesa AND fecha_no_asignacion > NOW()";
                $stmt = $pdo->prepare($sqlCheckAsignacion);
                $stmt->bindParam(':id_mesa', $id_mesa);
                $stmt->execute();
                $asignacion = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($asignacion) {
                    echo "<p><strong>Fecha de Inicio:</strong> " . htmlspecialchars($asignacion['fecha_asignacion']) . "</p>";
                    echo "<p><strong>Fecha de Fin:</strong> " . htmlspecialchars($asignacion['fecha_no_asignacion']) . "</p>";
                    echo "<p><strong>Asignada a:</strong> " . htmlspecialchars($asignacion['asignado_a']) . "</p>";

                    // Botón para deshacer una reserva
                    echo "<form method='POST' action=''>";
                    echo "<input type='hidden' name='mesa' value='$id_mesa'>";
                    echo "<input type='hidden' name='desasignar' value='true'>";
                    echo "<button type='submit' class='btn btn-danger'>Desasignar Mesa</button>";
                    echo "</form>";
                } else {
                    // Formulario para asignar la mesa si no está asignada
                    echo "<form method='POST' id='form-asignar' action='' class='text-left'>";
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
                    echo "</div>";
                    echo "<button type='submit' class='btn btn-success mt-3'>Asignar Mesa</button>";
                    echo "</form>";
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
