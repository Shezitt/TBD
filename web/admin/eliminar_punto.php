<?php
    require_once("../conexion.php");
    session_start();

    if (!in_array("dashboard", $_SESSION['permisos']) or !in_array("dashboard_gestion", $_SESSION['permisos'])) {
        header("Location: index.php");
        exit();
    }

    $idPunto = $_GET['id'];
    $stmt = $conn->prepare("UPDATE Punto_Reciclaje SET activo=0 WHERE idPunto = ?;");
    $stmt->bind_param("i", $idPunto);
    $stmt->execute();
    $stmt->close();
    header("Location: puntos.php");

?>