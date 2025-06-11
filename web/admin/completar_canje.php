<?php
    require_once("../conexion.php");
    session_start();

    if (!in_array("dashboard", $_SESSION['permisos']) or !in_array("dashboard_gestion", $_SESSION['permisos'])) {
        header("Location: index.php");
        exit();
    }
    $idCanje = $_GET['id'];
    echo $idCanje;
    $stmt = $conn->prepare("CALL sp_completarCanje(?);");
    $stmt->bind_param("i", $idCanje);
    $stmt->execute();
    $stmt->close();
    header("Location: canjes.php");

?>