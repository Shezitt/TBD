<?php
    require_once("../conexion.php");
    session_start();

    if (!in_array("dashboard", $_SESSION['permisos']) or !in_array("dashboard_gestion", $_SESSION['permisos'])) {
        header("Location: index.php");
        exit();
    }

    $idMaterial = $_GET['id'];
    $stmt = $conn->prepare("UPDATE Material SET activo=0 WHERE idMaterial = ?;");
    $stmt->bind_param("i", $idMaterial);
    $stmt->execute();
    $stmt->close();
    header("Location: materiales.php");

?>