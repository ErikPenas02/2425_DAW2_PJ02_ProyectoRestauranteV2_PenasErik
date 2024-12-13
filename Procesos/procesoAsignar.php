<?php
require_once "../Procesos/conection.php";
session_start();

// Verificar si hay sesión activa
if (!isset($_SESSION["usuarioAct"])) {
    header('Location: ../index.php');
    exit();
}

// Verificar si el formulario fue enviado
if (!isset($_POST["formAsignar"])) {
    header('Location: ../Paginas/asignar_mesa.php');
    exit();
}

$id_mesa = htmlspecialchars($_POST["mesa"]);
$assigned_to = htmlspecialchars($_POST['assigned_to']);
$fecha_inicio = htmlspecialchars($_POST['fecha_inicio']);
$fecha_fin = htmlspecialchars($_POST['fecha_fin']);

try {
    // Comprobar si hay un conflicto en las fechas
    $sqlCheckConflict = "SELECT * FROM tbl_historial 
                         WHERE id_recurso = :id_mesa 
                         AND (fecha_asignacion < :fecha_fin AND fecha_no_asignacion > :fecha_inicio)";
    $stmtCheck = $pdo->prepare($sqlCheckConflict);
    $stmtCheck->bindParam(':id_mesa', $id_mesa);
    $stmtCheck->bindParam(':fecha_inicio', $fecha_inicio);
    $stmtCheck->bindParam(':fecha_fin', $fecha_fin);
    $stmtCheck->execute();
    $conflicto = $stmtCheck->fetch(PDO::FETCH_ASSOC);

    if ($conflicto) {
        // Redirigir con mensaje de error si hay conflicto
        header('Location: ../Paginas/mesas.php?error=conflicto');
        exit();
    }

    // Insertar la nueva asignación si no hay conflictos
    $sqlAsignar = "INSERT INTO tbl_historial (id_recurso, fecha_asignacion, fecha_no_asignacion, asignado_por, asignado_a)
                   VALUES (:id_mesa, :fecha_inicio, :fecha_fin, :asignado_por, :assigned_to)";
    $stmtAsignar = $pdo->prepare($sqlAsignar);
    $stmtAsignar->bindParam(':id_mesa', $id_mesa);
    $stmtAsignar->bindParam(':fecha_inicio', $fecha_inicio);
    $stmtAsignar->bindParam(':fecha_fin', $fecha_fin);
    $stmtAsignar->bindParam(':asignado_por', $_SESSION["usuarioAct"]);
    $stmtAsignar->bindParam(':assigned_to', $assigned_to);
    $stmtAsignar->execute();

    // Redirigir con éxito
    header("Location: ../Paginas/mesas.php?exito=asignar");
    exit();
} catch (PDOException $e) {
    echo "<p>Error al asignar: " . htmlspecialchars($e->getMessage()) . "</p>";
}
?>
<?php
    // if (isset($_POST['desasignar']) && $_POST['desasignar'] === 'true') {
    // try {
            //     $sqlDesasignar = "DELETE FROM tbl_historial WHERE id_recurso = :id_mesa AND fecha_no_asignacion > NOW()";
            //     $stmtDesasignar = $pdo->prepare($sqlDesasignar);
            //     $stmtDesasignar->bindParam(':id_mesa', $id_mesa, PDO::PARAM_INT);
            //     $stmtDesasignar->execute();
            //     header("Location: ../Paginas/mesas.php?exito=desasignar");
            //     exit();
            // } catch (PDOException $e) {
            //     echo "<p>Error al desasignar: " . htmlspecialchars($e->getMessage()) . "</p>";
            // }

// }
// 
?>