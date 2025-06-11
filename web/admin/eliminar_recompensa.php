<?php
    require_once("../conexion.php");
    session_start();

    if (!in_array("dashboard", $_SESSION['permisos']) or !in_array("dashboard_gestion", $_SESSION['permisos'])) {
        header("Location: index.php");
        exit();
    }

    $idRecompensa = $_GET['id'];
    $stmt = $conn->prepare("UPDATE Recompensa SET activo=0 WHERE idRecompensa = ?;");
    $stmt->bind_param("i", $idRecompensa);
    $stmt->execute();
    $stmt->close();
    header("Location: catalogos.php");

?>