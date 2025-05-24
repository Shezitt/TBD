<?php
    require_once("../conexion.php");
    session_start();

    if (!(isset($_SESSION['rol']) && $_SESSION['rol'] == 2)) {
        header("Location: ../index.php");
    }

    $idCatalogo = $_GET['id'];
    $stmt = $conn->prepare("UPDATE Catalogo SET activo=0 WHERE idCatalogo = ?;");
    $stmt->bind_param("i", $idCatalogo);
    $stmt->execute();
    $stmt->close();
    header("Location: catalogos.php");

?>