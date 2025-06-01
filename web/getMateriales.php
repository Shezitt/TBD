<?php
require_once("conexion.php");

if (isset($_GET['idPunto'])) {
    $idPunto = intval($_GET['idPunto']);

    $stmt = $conn->prepare("CALL sp_getMaterialesPuntoReciclaje(?)");
    $stmt->bind_param("i", $idPunto);
    $stmt->execute();
    $result = $stmt->get_result();

    $materiales = [];
    while ($row = $result->fetch_assoc()) {
        $materiales[] = ['nombre' => $row['nombre']];
    }

    $stmt->close();
    $conn->close();

    header('Content-Type: application/json');
    echo json_encode($materiales);
}
?>
