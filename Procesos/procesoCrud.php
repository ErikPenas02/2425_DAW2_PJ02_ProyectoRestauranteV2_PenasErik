<?php
session_start();
require_once "../Procesos/conection.php";

if(isset($_SESSION["rolAct"]) && $_SESSION["rolAct"] !== 1){
    header("Location: ./inicio.php?rolAct=denied");
    exit();
}

if(!isset($_POST["edit_user"]) && !isset($_POST["crear_user"]) && !isset($_POST["edit_recurso"]) && !isset($_POST["crear_recurso"])){
    header("Location: ./users.php");
    exit();
}

if(isset($_POST["edit_user"])){
    try{
        $id_user = htmlspecialchars($_POST["id_usuario"]);
        $nombre = htmlspecialchars($_POST["nombre"]);
        $apellido = htmlspecialchars($_POST["apellido"]);
        $username = htmlspecialchars($_POST["username"]);
        $rol = htmlspecialchars($_POST["rol"]);

        $sqlCheckUsername = "SELECT COUNT(*) as total FROM tbl_usuarios WHERE username = :u_n AND id_usuario != :id_user";
        $stmt = $pdo->prepare($sqlCheckUsername);
        $stmt->bindParam(':u_n', $username);
        $stmt->bindParam(':id_user', $id_user);
        $stmt->execute();
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($resultado['total'] > 0) {
            // Si hay duplicados, no permitir la actualización
            header("Location: ../Paginas/users.php?error=username_exists");
            exit();
        }

        $sqlUPDUser = "UPDATE tbl_usuarios SET nombre_usuario = :n_user, apellido_usuario = :ap_user, username = :u_n, id_rol = :id_rol WHERE id_usuario = :id_user";
        $stmt = $pdo->prepare($sqlUPDUser);
        $stmt->bindParam(':n_user', $nombre);
        $stmt->bindParam(':ap_user', $apellido);
        $stmt->bindParam(':u_n', $username);
        $stmt->bindParam(':id_rol', $rol);
        $stmt->bindParam(':id_user', $id_user);
        $stmt->execute();

        header("Location: ../Paginas/users.php?exito=edit");
        exit();
    } catch (PDOException $e) {
        echo "Error al filtrar: " . $e->getMessage();
        die();
    }
    

}



?>