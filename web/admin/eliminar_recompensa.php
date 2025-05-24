<?php
    require_once("../conexion.php");
    session_start();

    if (!(isset($_SESSION['rol']) && $_SESSION['rol'] == 2)) {
        header("Location: ../index.php");
    }

    $idRecompensa = $_GET['id'];
    $stmt = $conn->prepare("UPDATE Recompensa SET activo=0 WHERE idRecompensa = ?;");
    $stmt->bind_param("i", $idRecompensa);
    $stmt->execute();
    $stmt->close();
    header("Location: catalogos.php");

?>