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
    <!-- Navbar ya implementado -->
    <header class="p-2 bg-dark text-white">
        <div class="container">
            <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start">
                <ul class="nav col-12 col-lg-auto me-lg-auto mb-2 justify-content-center mb-md-0">
                    <li><a href="#" class="nav-link px-2 text-secondary">Home</a></li>
                    <li><a href="./historial" class="nav-link px-2 text-white">Historial</a></li>
                </ul>
                <div class="text-end">
                    <button type="button" class="btn btn-outline-danger">Log Out</button>
                </div>
            </div>
        </div>
    </header>

    <!-- Opciones de la página -->
    <div class="opciones">
        <div class="opcion" id="opcionSalas">
            <span>SALAS</span>
        </div>
        
        <div class="opcion" id="opcionUsers">
            <span>USERS</span>
        </div>
        
        <div class="opcion" id="opcionRecursos">
            <span>RECURSOS</span>
        </div>
    </div>
    <div class="mensajeInicio"><p>Solo los Administradores de la Empresa pueden acceder a USERS y RECURSOS</p></div>

    <!-- SweetAlert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="../JS/alertIndex.js"></script>
</body>
</html>

<?php
    // require_once "../Procesos/conection.php";
    // session_start();
    // // Sesión iniciada
    // if (!isset($_SESSION["camareroID"])) {
    //     header('Location: ../index.php?error=nosesion');
    //     exit();
    // } else {
    //     $id_user = $_SESSION["camareroID"];
    // }
    // // Consulta SQL para obtener las salas y contar las mesas libres
    // $consulta = "
    //     SELECT s.name_sala, 
    //             COUNT(m.id_mesa) AS total_mesas, 
    //             SUM(CASE WHEN h.fecha_A IS NULL THEN 1 ELSE 0 END) AS mesas_libres
    //     FROM tbl_salas s
    //     LEFT JOIN tbl_mesas m ON s.id_salas = m.id_sala
    //     LEFT JOIN tbl_historial h ON m.id_mesa = h.id_mesa AND h.fecha_NA IS NULL
    //     GROUP BY s.id_salas
    // ";
    // $stmt = $conn->prepare($consulta);
    // // Ejecutar la consulta
    // if ($stmt->execute()) {
    //     // Obtener los resultados
    //     $resultado = $stmt->get_result();
    //     // Generación de botones para cada sala con el conteo de mesas libres
    //     if ($resultado->num_rows > 0) {
    //         while ($fila = $resultado->fetch_assoc()) {
    //             $nombre_sala = htmlspecialchars($fila['name_sala']); // Sanitizar el nombre de la sala
    //             $total_mesas = $fila['total_mesas'];
    //             $mesas_libres = $fila['mesas_libres'];
    //             echo "<input type='submit' name='sala' value='$nombre_sala' class='input_sala input_$nombre_sala'>";
    //             echo "<p class='input_sala2 mesas_disponibles_$nombre_sala'>($mesas_libres/$total_mesas)</p>";
    //         }
    //     } else {
    //         echo "<p>No hay salas disponibles</p>";
    //     }
    // } else {
    //     echo "<p>Error al ejecutar la consulta</p>";
    // }
    // // Cerrar la declaración y la conexión
    // $stmt->close();
    ?>
<!-- <div class="footer">
            <a href="../Procesos/destruir.php"><button type="submit" class="logout">Cerrar Sesión</button></a>
            <a href="./historial"><button type="submit" class="back">Historial</button></a>
            <h1>¡Selecciona una sala para ver su disponibilidad de mesas!</h1>
        </div> -->