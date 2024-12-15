<?php
session_start();
require_once "../Procesos/conection.php";

if(isset($_SESSION["rolAct"]) && $_SESSION["rolAct"] !== 1){
    header("Location: ./inicio.php?rolAct=denied");
    exit();
}

if(!isset($_POST["edit_user"]) && !isset($_POST["crear_user"]) 
    && !isset($_POST["edit_sala"]) && !isset($_POST["crear_sala"])
    && !isset($_POST["edit_recurso"]) && !isset($_POST["crear_recurso"])){
    header("Location: ./users.php");
    exit();
}

// Editar User
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
            header("Location: ../Paginas/editar.php?error=username_exists");
            exit();
        }

        $sqlUPDUser = "UPDATE tbl_usuarios SET nombre_usuario = :n_user, apellido_usuario = :ap_user, username = :u_n, id_rol = :id_rol WHERE id_usuario = :id_user";
        $stmt2 = $pdo->prepare($sqlUPDUser);
        $stmt2->bindParam(':n_user', $nombre);
        $stmt2->bindParam(':ap_user', $apellido);
        $stmt2->bindParam(':u_n', $username);
        $stmt2->bindParam(':id_rol', $rol);
        $stmt2->bindParam(':id_user', $id_user);
        $stmt2->execute();

        header("Location: ../Paginas/users.php?exito=edit");
        exit();
    } catch (PDOException $e) {
        echo "Error al filtrar: " . $e->getMessage();
        die();
    }
    

}

// Crear Salas
if(isset($_POST["crear_user"])){
    try{
        $id_user = htmlspecialchars($_POST["id_usuario"]);
        $nombre = htmlspecialchars($_POST["nombre"]);
        $apellido = htmlspecialchars($_POST["apellido"]);
        $username = htmlspecialchars($_POST["username"]);
        $rol = htmlspecialchars($_POST["rol"]);
        $password = hash('sha256', htmlspecialchars($_POST["pwd"]));

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

        $sqlInsertUser = "INSERT INTO tbl_usuarios (nombre_usuario, apellido_usuario, username, password, id_rol) 
                  VALUES (:n_user, :ap_user, :u_n, :pwd, :id_rol)";

        $stmt2 = $pdo->prepare($sqlInsertUser);
        $stmt2->bindParam(':n_user', $nombre);
        $stmt2->bindParam(':ap_user', $apellido);
        $stmt2->bindParam(':u_n', $username);
        $stmt2->bindParam(':pwd', $password);
        $stmt2->bindParam(':id_rol', $rol);
        $stmt2->execute();

        header("Location: ../Paginas/users.php?exito=crear");
        exit();
    } catch (PDOException $e) {
        echo "Error al filtrar: " . $e->getMessage();
        die();
    }
}
// Editar Sala
if(isset($_POST["edit_sala"])){
    try {
        $id_sala = htmlspecialchars($_POST["id_sala"]);
        $nombre_sala = htmlspecialchars($_POST["nombre_sala"]);
        $tipo_sala = htmlspecialchars($_POST["tipo_sala"]);

        $sqlUPDSala = "UPDATE tbl_salas 
                       SET nombre_sala = :nombre_sala, tipo_sala = :tipo_sala 
                       WHERE id_sala = :id_sala";
        $stmt = $pdo->prepare($sqlUPDSala);
        $stmt->bindParam(':nombre_sala', $nombre_sala);
        $stmt->bindParam(':tipo_sala', $tipo_sala);
        $stmt->bindParam(':id_sala', $id_sala, PDO::PARAM_INT);
        $stmt->execute();

        header("Location: ../Paginas/recursos.php?exito=edit_sala");
        exit();
    } catch (PDOException $e) {
        echo "Error al editar la sala: " . $e->getMessage();
        die();
    }
}
if(isset($_POST["crear_sala"])){
    try{
        $nombre_sala = htmlspecialchars($_POST["nombre_sala"]);
        $tipo_sala = htmlspecialchars($_POST["tipo_sala"]);

        $sqlINSSalas = "INSERT INTO tbl_salas (nombre_sala, tipo_sala) VALUES (:nombre_sala, :tipo_sala);";
        $stmt = $pdo->prepare($sqlINSSalas);
        $stmt->bindParam(':nombre_sala', $nombre_sala);
        $stmt->bindParam(':tipo_sala', $tipo_sala);
        $stmt->execute();

        header("Location: ../Paginas/recursos.php?exito=crearSala");
        exit();
    } catch (PDOException $e) {
        echo "Error al filtrar: " . $e->getMessage();
        die();
    }
}

// Editar Recurso
if(isset($_POST["edit_recurso"])){
    try {
        $id_recurso = htmlspecialchars($_POST["id_recurso"]);
        $nombre_recurso = htmlspecialchars($_POST["nombre_recurso"]);
        $tipo_recurso = htmlspecialchars($_POST["tipo_recurso"]);
        $id_sala = !empty($_POST["id_sala"]) ? htmlspecialchars($_POST["id_sala"]) : null;

        $sqlUPDRecurso = "UPDATE tbl_recursos 
                          SET nombre_recurso = :nombre_recurso, tipo_recurso = :tipo_recurso, id_sala = :id_sala 
                          WHERE id_recurso = :id_recurso";
        $stmt = $pdo->prepare($sqlUPDRecurso);
        $stmt->bindParam(':nombre_recurso', $nombre_recurso);
        $stmt->bindParam(':tipo_recurso', $tipo_recurso);
        $stmt->bindParam(':id_sala', $id_sala, PDO::PARAM_INT);
        $stmt->bindParam(':id_recurso', $id_recurso, PDO::PARAM_INT);
        $stmt->execute();

        header("Location: ../Paginas/recursos.php?exito=edit_recurso");
        exit();
    } catch (PDOException $e) {
        echo "Error al editar el recurso: " . $e->getMessage();
        die();
    }
}

// Crear Recurso
if(isset($_POST["crear_sala"])){
    try{
        $nombre_recurso = htmlspecialchars($_POST["nombre_sala"]);
        $tipo_recurso = htmlspecialchars($_POST["tipo_sala"]);
        $id_sala = !empty($_POST["id_sala"]) ? htmlspecialchars($_POST["id_sala"]) : null;
        $id_padre = !empty($_POST["id_sala"]) ? htmlspecialchars($_POST["id_sala"]) : null;

        $sqlINSSalas = "INSERT INTO tbl_recursos (nombre_recurso, tipo_recurso, id_padre, id_sala) VALUES (:nombre_recurso, :tipo_recurso, :id_padre, :id_sala);";
        $stmt = $pdo->prepare($sqlINSSalas);
        $stmt->bindParam(':nombre_recurso', $nombre_recurso);
        $stmt->bindParam(':tipo_recurso', $tipo_recurso);
        $stmt->bindParam(':id_padre', $id_padre);
        $stmt->bindParam(':id_sala', $id_sala);
        $stmt->execute();

        header("Location: ../Paginas/recursos.php?exito=crearRecurso");
        exit();
    } catch (PDOException $e) {
        echo "Error al filtrar: " . $e->getMessage();
        die();
    }
}

?>