<?php
    $host = "localhost";
    $user = "root";
    $password = "";

    // try {
    //     $conn = new mysqli($server, $user, $pwd, $db);

    //     // Verifica la conexión
    //     if ($conn->connect_error) {
    //         throw new Exception("Connection failed: " . $conn->connect_error);
    //     }

    // } catch (Exception $e) {
    //     // Manejo de errores
    //     die("Error: " . $e->getMessage());
    // }
    try {
        $pdo = new PDO("mysql:host=$host;dbname=db_restaurante_v2", $user, $password);
        
    } catch (PDOException $e) {
        // Capturar cualquier excepción y mostrar el mensaje de error
        echo "Error en la conexión: " . $e->getMessage();
        echo "</br>";
        die("Conexión fallida.");
    }