<?php

$conn = new mysqli("localhost", "shamir", "", "reciclaje");

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
} 