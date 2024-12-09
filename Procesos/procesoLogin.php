<?php
session_start();

include_once("./conection.php");

if (!filter_has_var(INPUT_POST, 'enviar')) {
    header("Location: ../index.php?error=inicioMal");
    exit();
}

$usr = htmlspecialchars($_POST["username"]);
$pwd = htmlspecialchars($_POST["pwd"]);

try {
    // Consulta SQL con marcador de parámetro
    $sqlInicio = "SELECT id_usuario, password, id_rol FROM tbl_usuarios WHERE username = :username";

    // Preparar la consulta
    $stmt = $pdo->prepare($sqlInicio);

    // Vincular el parámetro
    $stmt->bindParam(':username', $usr);

    // Ejecutar la consulta
    $stmt->execute();

    // Obtener los resultados
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    // Validación
    if (!$result) {
        // Usuario no encontrado
        header("Location: ../index.php?error=datosMal");
        exit();
    }

    // Verificar la contraseña
    $pwdBBDD = $result["password"];
    // echo $pwdBBDD . "<br>";
    // echo $pwd;
    // exit();

    if (hash('sha256', $_POST["pwd"]) !== $pwdBBDD) {
        header("Location: ../index.php?error=datosMal");
        exit();
    }

    // Inicio de sesión exitoso
    $_SESSION["usuarioAct"] = $result["id_usuario"];
    $_SESSION["rolAct"] = $result["id_rol"];
    header("Location: ../Paginas/inicio.php?login=success");
    exit();

} catch (PDOException $e) {
    echo "Error al iniciar sesión: " . $e->getMessage();
    die();
}
