<?php

$conn = new mysqli("localhost", "root", "password", "reciclaje");
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
} 
?>


