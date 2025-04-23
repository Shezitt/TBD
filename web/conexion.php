<?php

class Conexion {
    private static $conn = null;

    public static function getConexion() {
        self::$conn = new mysqli("localhost", "root", "password", "reciclaje");
        if (self::$conn->connect_error) {
            die("Conexión fallida: " . self::$conn->connect_error);
        }
        return self::$conn;
    }
}
