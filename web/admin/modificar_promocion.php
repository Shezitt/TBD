<?php
session_start();

if (!(isset($_SESSION['rol']) && $_SESSION['rol'] == 2)) {
    header("Location: ../index.php");
}

require_once("../conexion.php");

if (!isset($_GET['id'])) {
    header("Location: promociones.php");
    exit();
}

$idPromocion = $_GET['id'];

$stmt = $conn->prepare("SELECT * FROM Promocion WHERE idPromocion = ?");
$stmt->bind_param("i", $idPromocion);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado->num_rows === 0) {
    echo "Promoción no encontrada.";
    exit();
}

$promocion = $resultado->fetch_assoc();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Modificar Promoción</title>
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
            max-width: 800px;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            padding: 25px 35px;
            margin-top: 40px;
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

        form {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            align-items: flex-end;
        }

        form input[type="text"],
        form input[type="number"],
        form input[type="date"] {
            flex: 1;
            min-width: 150px;
            padding: 10px 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
            background-color: #e0f2f7;
            color: #333;
            box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.05);
        }

        form input[type="submit"] {
            background-color: #007bff;
            color: white;
            padding: 10px 25px;
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
            margin-top: 30px;
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
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .back-button:hover {
            background-color: #2c3e50;
        }

        h2 {
            font-size: 20px;
            color: #007bff;
            margin-top: 0;
            margin-bottom: 20px;
        }

        label {
            font-size: 14px;
            color: #555;
        }

        .form-group {
            flex: 1 1 45%;
            display: flex;
            flex-direction: column;
        }
    </style>
</head>

<body>
<div class="container">
    <div class="header">
        <i class="fas fa-edit icon"></i>
        <h1>MODIFICAR PROMOCIÓN</h1>
    </div>

    <h2>Editar datos de la promoción</h2>

    <form method="POST">
        <div class="form-group">
            <label>Nombre:</label>
            <input type="text" name="nombre" value="<?php echo htmlspecialchars($promocion['nombre']); ?>" required>
        </div>

        <div class="form-group">
            <label>Multiplicador:</label>
            <input type="number" name="multiplicador" step="0.01" value="<?php echo htmlspecialchars($promocion['multiplicador']); ?>" required>
        </div>

        <div class="form-group">
            <label>Nivel Requerido:</label>
            <input type="number" name="nivel_requerido" value="<?php echo htmlspecialchars($promocion['nivelRequerido']); ?>" required>
        </div>

        <div class="form-group">
            <label>Fecha Inicio:</label>
            <input type="date" name="fecha_inicio" value="<?php echo htmlspecialchars($promocion['fechaInicio']); ?>" required>
        </div>

        <div class="form-group">
            <label>Fecha Fin:</label>
            <input type="date" name="fecha_fin" value="<?php echo htmlspecialchars($promocion['fechaFin']); ?>" required>
        </div>

        <input type="submit" name="actualizar" value="Guardar Cambios">
    </form>

    <div class="back-button-container">
        <a href="promociones.php" class="back-button">Volver</a>
    </div>
</div>
</body>
</html>

<?php
if (isset($_POST['actualizar'])) {
    if (isset($_POST['actualizar'])) {
    $nombre = $_POST['nombre'];
    $multiplicador = $_POST['multiplicador'];
    $nivel_requerido = $_POST['nivel_requerido'];
    $fecha_inicio = $_POST['fecha_inicio'];
    $fecha_fin = $_POST['fecha_fin'];

    $stmt = $conn->prepare("UPDATE Promocion SET nombre = ?, multiplicador = ?, nivelRequerido = ?, fechaInicio = ?, fechaFin = ? WHERE idPromocion = ?");
    $stmt->bind_param("sddssi", $nombre, $multiplicador, $nivel_requerido, $fecha_inicio, $fecha_fin, $idPromocion);
    $stmt->execute();
    $stmt->close();

    echo "<script>alert('Promoción modificada exitosamente.'); window.location.href='promociones.php';</script>";
    exit();
}
}
?>
