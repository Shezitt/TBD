<?php

$conn = new mysqli("localhost", "root", "password", "reciclaje");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

