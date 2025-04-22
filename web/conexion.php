<?php

$host = "localhost";
$user = "shamir";
$password = "";
$database = "reciclaje";

$conn = new mysqli($host, $user, $password, $database);

if ($conn->connect_error) {
    die("Conexion fallida: " . $conn.connect->error);
}