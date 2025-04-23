<?php

$conn = new mysqli("localhost", "shamir", "", "reciclaje");

<<<<<<< HEAD
    public static function getConexion() {
        self::$conn = new mysqli("localhost", "root", "password", "reciclaje");
        if (self::$conn->connect_error) {
            die("Conexión fallida: " . self::$conn->connect_error);
        }
        return self::$conn;
    }
}
=======
if ($conn->connect_error) {
    die("Conexión fallida: " . self::$conn->connect_error);
} 
>>>>>>> a5ade2f81f96e279b19bb7e665e2a34e40504549
