<?php
    require_once("../conexion.php");
    session_start();

    if (!(isset($_SESSION['rol']) && $_SESSION['rol'] == 2)) {
        header("Location: ../index.php");
    }

    $idCanje = $_GET['id'];
    echo $idCanje;
    $stmt = $conn->prepare("CALL sp_completarCanje(?);");
    $stmt->bind_param("i", $idCanje);
    $stmt->execute();
    $stmt->close();
    header("Location: canjes.php");

?>