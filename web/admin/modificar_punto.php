<?php
require_once("../conexion.php");
session_start();

if (!in_array("dashboard", $_SESSION['permisos']) or !in_array("dashboard_gestion", $_SESSION['permisos'])) {
    header("Location: index.php");
    exit();
}


$idPunto = $_GET['id'];

$stmt = $conn->prepare("SELECT nombre, latitud, longitud, apertura, cierre FROM Punto_Reciclaje WHERE idPunto = ?");
$stmt->bind_param("i", $idPunto);
$stmt->execute();
$stmt->bind_result($nombrePunto, $latitud, $longitud, $horaApertura, $horaCierre);
$stmt->fetch();
$stmt->close();

$materiales = [];
$result = $conn->query("SELECT idMaterial, nombre FROM Material WHERE activo = 1");
while ($row = $result->fetch_assoc()) {
    $materiales[] = $row;
}
$result->close();

$materialesAceptados = [];
$stmt = $conn->prepare("CALL sp_getMaterialesPuntoReciclaje(?)");
$stmt->bind_param("i", $idPunto);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $materialesAceptados[] = $row['idMaterial'];
}
$stmt->close();

if (isset($_POST['modificar_materiales'])) {
    $stmt = $conn->prepare("DELETE FROM Punto_Reciclaje_Materiales WHERE idPunto = ?");
    $stmt->bind_param("i", $idPunto);
    $stmt->execute();
    $stmt->close();

    if (!empty($_POST['materiales'])) {
        $stmt = $conn->prepare("INSERT INTO Punto_Reciclaje_Materiales (idPunto, idMaterial) VALUES (?, ?)");
        foreach ($_POST['materiales'] as $idMaterial) {
            $stmt->bind_param("ii", $idPunto, $idMaterial);
            $stmt->execute();
        }
        $stmt->close();
    }

    echo "<script>alert('Materiales actualizados correctamente.'); window.location.href=window.location.href;</script>";
}

if (isset($_POST['modificar_datos'])) {
    $nuevoNombre = $_POST['nombre'];
    $nuevaLatitud = $_POST['latitud'];
    $nuevaLongitud = $_POST['longitud'];
    $nuevaApertura = $_POST['hora_apertura'];
    $nuevaCierre = $_POST['hora_cierre'];

    $stmt = $conn->prepare("UPDATE Punto_Reciclaje SET nombre = ?, latitud = ?, longitud = ?, apertura = ?, cierre = ? WHERE idPunto = ?");
    $stmt->bind_param("sddssi", $nuevoNombre, $nuevaLatitud, $nuevaLongitud, $nuevaApertura, $nuevaCierre, $idPunto);
    $stmt->execute();
    $stmt->close();

    echo "<script>alert('Datos del punto actualizados correctamente.'); window.location.href=window.location.href;</script>";
}

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Modificar Materiales - Punto de Reciclaje</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f0f2f5;
        margin: 0;
        padding: 0;
        display: flex;
        justify-content: center;
        align-items: flex-start;
        min-height: 100vh;
    }

    .container {
        width: 90%;
        max-width: 1000px;
        background-color: #ffffff;
        border-radius: 8px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        padding: 25px 35px;
        margin-top: 30px;
        margin-bottom: 30px;
    }

    .header {
        display: flex;
        align-items: center;
        padding-bottom: 20px;
        border-bottom: 1px solid #eee;
        margin-bottom: 25px;
    }

    .header .icon {
        font-size: 30px;
        color: #4CAF50;
        margin-right: 15px;
    }

    .header h1 {
        font-size: 26px;
        color: #333;
        margin: 0;
    }

    h2 {
        font-size: 20px;
        color: #007bff;
        margin-top: 0;
        margin-bottom: 20px;
    }

    .form-section {
        margin-bottom: 40px;
    }

    .add-point-form {
        display: flex;
        flex-wrap: wrap;
        gap: 15px;
        margin-bottom: 25px;
        align-items: flex-start;
    }

    .add-point-form label {
        font-size: 16px;
        color: #555;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .add-point-form input[type="text"],
    .add-point-form input[type="number"],
    .add-point-form input[type="time"] {
        padding: 10px 15px;
        border: 1px solid #ccc;
        border-radius: 5px;
        font-size: 16px;
        background-color: #e0f2f7;
        color: #333;
        box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.05);
        width: 100%;
        max-width: 300px;
    }

    .form-section input[type="submit"] {
        background-color: #007bff;
        color: white;
        padding: 10px 25px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-size: 16px;
        font-weight: bold;
        transition: background-color 0.3s ease;
        margin-top: 10px;
    }

    .form-section input[type="submit"]:hover {
        background-color: #0056b3;
    }

    .back-button-container {
        display: flex;
        justify-content: flex-start;
        margin-top: 20px;
    }

    .back-button {
        background-color: #6c757d;
        color: white;
        padding: 10px 20px;
        border: none;
        border-radius: 5px;
        text-decoration: none;
        font-size: 16px;
        font-weight: bold;
        transition: background-color 0.3s ease;
    }

    .back-button:hover {
        background-color: #5a6268;
    }

    input[type="checkbox"] {
        transform: scale(1.2);
        cursor: pointer;
    }
    .formularios {
        display: flex;
        flex-direction: row;
        justify-content: space-around;
    }
</style>
</head>

<body>
    <div class="container">
        <div class="header">
            <i class="fas fa-recycle icon"></i>
            <h1>MODIFICAR PUNTO DE RECICLAJE: <?php echo htmlspecialchars($nombrePunto); ?></h1>
        </div>

        <div class="formularios">
            
            <form method="POST" class="form-section">
                <h2>Modificar datos del punto:</h2>
                <div class="add-point-form" style="flex-direction: column; align-items: flex-start;">
                    <label>Nombre:</label>
                    <input type="text" name="nombre" value="<?php echo htmlspecialchars($nombrePunto); ?>" required>

                    <label>Latitud:</label>
                    <input type="number" name="latitud" step="0.000001" value="<?php echo $latitud; ?>" required>
                    <label>Longitud:</label>
                    <input type="number" name="longitud" step="0.000001" value="<?php echo $longitud; ?>" required>

                    <label>Hora de apertura:</label>
                    <input type="time" name="hora_apertura" value="<?php echo $horaApertura; ?>" required>

                    <label>Hora de cierre:</label>
                    <input type="time" name="hora_cierre" value="<?php echo $horaCierre; ?>" required>
                </div>
                <input name="modificar_datos" type="submit" value="Guardar Cambios">
            </form>

            <form method="POST" class="form-section">
                <h2>Selecciona los materiales aceptados:</h2>
                <div class="add-point-form" style="flex-direction: column; align-items: flex-start;">
                    <?php foreach ($materiales as $material) : ?>
                        <label style="font-size: 16px;">
                            <input type="checkbox" name="materiales[]" value="<?php echo $material['idMaterial']; ?>"
                                <?php if (in_array($material['idMaterial'], $materialesAceptados)) echo 'checked'; ?>>
                            <?php echo htmlspecialchars($material['nombre']); ?>
                        </label>
                    <?php endforeach; ?>
                </div>
                <input name="modificar_materiales" type="submit" value="Guardar Cambios">
            </form>

        </div>
        

        <div class="back-button-container">
            <a href="puntos.php" class="back-button">Volver</a>
        </div>
    </div>
</body>

</html>
