<?php
    require_once("../conexion.php");
    session_start();

    if (!(isset($_SESSION['rol']) && $_SESSION['rol'] == 2)) {
        header("Location: ../index.php");
    }

    $idMaterial = $_GET['id'];
    $stmt = $conn->prepare("UPDATE Material SET activo=0 WHERE idMaterial = ?;");
    $stmt->bind_param("i", $idMaterial);
    $stmt->execute();
    $stmt->close();
    header("Location: materiales.php");

?>