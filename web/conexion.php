<?php

$conn = new mysqli("localhost", "shamir", "", "reciclaje");
$conn->set_charset("utf8mb4");

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
} 
?>


