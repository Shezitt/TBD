<?php

$conn = new mysqli("localhost", "shamir", "", "reciclaje");

if ($conn->connect_error) {
    die("Conexión fallida: " . self::$conn->connect_error);
} 