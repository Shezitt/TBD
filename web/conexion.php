<?php

$conn = new mysqli("localhost", "root", "", "reciclaje");

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
} 