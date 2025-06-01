<?php
    require_once("../conexion.php");
    session_start();

    if (!(isset($_SESSION['rol']) && $_SESSION['rol'] == 2)) {
        header("Location: ../index.php");
    }

    $idNivel = $_GET['id'];
    $stmt = $conn->prepare("DELETE FROM Nivel WHERE idNivel = ?;");
    $stmt->bind_param("i", $idNivel);
    $stmt->execute();
    $stmt->close();
    header("Location: niveles.php");

?>