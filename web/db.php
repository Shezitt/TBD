<?php
$servername = "localhost"; // El servidor donde está alojada la base de datos
$username = "root";        // Tu nombre de usuario de la base de datos
$password = "password";            // Tu contraseña de la base de datos
$dbname = "reciclaje";     // El nombre de tu base de datos

// Crear la conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Comprobar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
} else {
    echo "Conexión exitosa";
}
?>


