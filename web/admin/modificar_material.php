<?php
session_start();

if (!(isset($_SESSION['rol']) && $_SESSION['rol'] == 2)) {
    header("Location: ../index.php");
}

require_once("../conexion.php");

if (!isset($_GET['id'])) {
    header("Location: materiales.php");
    exit();
}

$idMaterial = $_GET['id'];

$stmt = $conn->prepare("SELECT * FROM Material WHERE idMaterial = ? AND activo = 1");
$stmt->bind_param("i", $idMaterial);
$stmt->execute();
$resultado = $stmt->get_result();
$material = $resultado->fetch_assoc();
$stmt->close();

if (!$material) {
    echo "<script>alert('Material no encontrado.'); window.location.href='materiales.php';</script>";
    exit();
}

if (isset($_POST['modificar_material'])) {
    $nombre = $_POST['nombre'];
    $coeficiente_puntos = $_POST['coeficiente_puntos'];
    $coeficiente_co2 = $_POST['coeficiente_impacto_co2'];
    $coeficiente_agua = $_POST['coeficiente_impacto_agua'];
    $coeficiente_energia = $_POST['coeficiente_impacto_energia'];

    $stmt = $conn->prepare("UPDATE Material SET nombre = ?, coeficientePuntos = ?, coeficienteCO2 = ?, coeficienteAgua = ?, coeficienteEnergia = ? WHERE idMaterial = ?");
    $stmt->bind_param("sddddi", $nombre, $coeficiente_puntos, $coeficiente_co2, $coeficiente_agua, $coeficiente_energia, $idMaterial);
    $stmt->execute();
    $stmt->close();

    echo "<script>alert('Material modificado exitosamente.'); window.location.href='materiales.php';</script>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modificar Material</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        /* Mismo CSS que materiales.php */
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
            max-width: 600px;
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
            font-size: 28px;
            color: #333;
            margin: 0;
        }

        h2 {
            font-size: 22px;
            color: #007bff;
            margin-top: 0;
            margin-bottom: 20px;
        }

        form input[type="text"],
        form input[type="number"] {
            width: 100%;
            padding: 10px 15px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
            background-color: #e0f2f7;
            color: #333;
        }

        form input[type="submit"] {
            background-color: #007bff;
            color: white;
            padding: 12px 25px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            font-weight: bold;
            transition: background-color 0.3s ease;
        }

        form input[type="submit"]:hover {
            background-color: #0056b3;
        }

        .back-button-container {
            margin-top: 25px;
            text-align: left;
        }

        .back-button {
            display: inline-block;
            background-color: #34495e;
            color: white;
            padding: 12px 25px;
            border-radius: 5px;
            text-decoration: none;
            font-size: 16px;
            font-weight: bold;
            transition: background-color 0.3s ease;
        }

        .back-button:hover {
            background-color: #2c3e50;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <i class="fas fa-pencil-alt icon"></i>
            <h1>MODIFICAR MATERIAL</h1>
        </div>

        <h2>Editar datos del material</h2>

        <form method="POST">
            <input type="text" name="nombre" value="<?php echo htmlspecialchars($material['nombre']); ?>" required>
            <input type="number" name="coeficiente_puntos" step="0.01" value="<?php echo htmlspecialchars($material['coeficientePuntos']); ?>" required>
            <input type="number" name="coeficiente_impacto_co2" step="0.01" value="<?php echo htmlspecialchars($material['coeficienteCO2']); ?>" required>
            <input type="number" name="coeficiente_impacto_agua" step="0.01" value="<?php echo htmlspecialchars($material['coeficienteAgua']); ?>" required>
            <input type="number" name="coeficiente_impacto_energia" step="0.01" value="<?php echo htmlspecialchars($material['coeficienteEnergia']); ?>" required>
            <input type="submit" name="modificar_material" value="Guardar Cambios">
        </form>

        <div class="back-button-container">
            <a href="materiales.php" class="back-button">Cancelar</a>
        </div>
    </div>
</body>

</html>
