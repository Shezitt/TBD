<?php
    require_once("../conexion.php");
    session_start();

    if (!in_array("dashboard", $_SESSION['permisos']) or !in_array("dashboard_gestion", $_SESSION['permisos'])) {
        header("Location: index.php");
        exit();
    }

    $idCatalogo = $_GET['id'];
    $stmt = $conn->prepare("UPDATE Catalogo SET activo=0 WHERE idCatalogo = ?;");
    $stmt->bind_param("i", $idCatalogo);
    $stmt->execute();
    $stmt->close();
    header("Location: catalogos.php");

?>