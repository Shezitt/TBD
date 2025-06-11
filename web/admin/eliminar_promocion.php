<?php
    require_once("../conexion.php");
    session_start();

    if (!in_array("dashboard", $_SESSION['permisos']) or !in_array("dashboard_gestion", $_SESSION['permisos'])) {
        header("Location: index.php");
        exit();
    }

    $idPromocion = $_GET['id'];
    $stmt = $conn->prepare("UPDATE Promocion SET activo=0 WHERE idPromocion = ?;");
    $stmt->bind_param("i", $idPromocion);
    $stmt->execute();
    $stmt->close();
    header("Location: promociones.php");

?>