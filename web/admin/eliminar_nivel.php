<?php
    require_once("../conexion.php");
    session_start();

    if (!in_array("dashboard", $_SESSION['permisos']) or !in_array("dashboard_gestion", $_SESSION['permisos'])) {
        header("Location: index.php");
        exit();
    }

    $idNivel = $_GET['id'];
    $stmt = $conn->prepare("DELETE FROM Nivel WHERE idNivel = ?;");
    $stmt->bind_param("i", $idNivel);
    $stmt->execute();
    $stmt->close();
    header("Location: niveles.php");

?>