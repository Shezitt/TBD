<?php
    require_once("../conexion.php");
    session_start();

    if (!(isset($_SESSION['rol']) && $_SESSION['rol'] == 2)) {
        header("Location: ../index.php");
    }

    $idPromocion = $_GET['id'];
    $stmt = $conn->prepare("UPDATE Promocion SET activo=0 WHERE idPromocion = ?;");
    $stmt->bind_param("i", $idPromocion);
    $stmt->execute();
    $stmt->close();
    header("Location: promociones.php");

?>