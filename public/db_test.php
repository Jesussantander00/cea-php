<?php
// public/db_test.php

$host = "localhost";      // o 127.0.0.1
$dbname = "cea_php";
$user = "root";           
$password = "Daniel260722";   // tu contraseña real de MySQL

// Crear conexión con PDO
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "✅ Conexión exitosa a la base de datos<br>";

    // Probar una consulta
    $stmt = $pdo->query("SELECT * FROM users");
    $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (empty($usuarios)) {
        echo "⚠️ La tabla users está vacía.";
    } else {
        echo "<pre>";
        print_r($usuarios);
        echo "</pre>";
    }

} catch (PDOException $e) {
    echo "❌ Error de conexión: " . $e->getMessage();
}
