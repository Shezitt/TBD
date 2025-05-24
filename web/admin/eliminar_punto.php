<?php
    require_once("../conexion.php");
    session_start();

    if (!(isset($_SESSION['rol']) && $_SESSION['rol'] == 2)) {
        header("Location: ../index.php");
    }

    $idPunto = $_GET['id'];
    $stmt = $conn->prepare("UPDATE Punto_Reciclaje SET activo=0 WHERE idPunto = ?;");
    $stmt->bind_param("i", $idPunto);
    $stmt->execute();
    $stmt->close();
    header("Location: puntos.php");

?>