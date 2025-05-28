<?php
session_start();

if (!(isset($_SESSION['rol']) && $_SESSION['rol'] == 2)) {
    header("Location: ../index.php");
}

require_once("../conexion.php");

// Validar que venga el ID
if (!isset($_GET['id'])) {
    header("Location: catalogos.php");
    exit();
}

$idCatalogo = $_GET['id'];

// Obtener los datos actuales del catálogo
$catalogo = null;
if ($stmt = $conn->prepare("SELECT nombreCatalogo FROM Catalogo WHERE idCatalogo = ? AND activo = '1'")) {
    $stmt->bind_param("i", $idCatalogo);
    $stmt->execute();
    $result = $stmt->get_result();
    $catalogo = $result->fetch_assoc();
    $stmt->close();
} else {
    error_log("Error al preparar SELECT: " . $conn->error);
}

if (!$catalogo) {
    header("Location: catalogos.php");
    exit();
}

// Si se envió el formulario para modificar
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['nombre_catalogo'])) {
    $nuevoNombre = trim($_POST['nombre_catalogo']);

    if (!empty($nuevoNombre)) {
        if ($stmt = $conn->prepare("UPDATE Catalogo SET nombreCatalogo = ? WHERE idCatalogo = ?")) {
            $stmt->bind_param("si", $nuevoNombre, $idCatalogo);
            if ($stmt->execute()) {
                header("Location: catalogos.php");
                exit();
            } else {
                $error = "Error al actualizar catálogo.";
            }
            $stmt->close();
        } else {
            error_log("Error al preparar UPDATE: " . $conn->error);
            $error = "Error en la base de datos.";
        }
    } else {
        $error = "El nombre no puede estar vacío.";
    }
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modificar Catálogo</title>
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
            color: #28a745;
            margin-right: 15px;
        }

        .header h1 {
            font-size: 28px;
            color: #333;
            margin: 0;
        }

        h2 {
            font-size: 20px;
            color: #007bff;
            margin-top: 0;
            margin-bottom: 20px;
        }

        .add-form {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            margin-bottom: 30px;
            align-items: flex-end;
        }

        .add-form input[type="text"],
        .add-form input[type="number"],
        .add-form select {
            flex: 1;
            min-width: 180px;
            padding: 10px 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
            background-color: #e0f2f7;
            color: #333;
            box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.05);
        }

        .add-form input[type="submit"] {
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

        .add-form input[type="submit"]:hover {
            background-color: #0056b3;
        }

        .table-container {
            border: 2px solid #007bff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
        }

        .catalog-table,
        .reward-table {
            width: 100%;
            border-collapse: collapse;
            margin: 0;
        }

        .catalog-table thead th,
        .reward-table thead th {
            background-color: #007bff;
            color: white;
            padding: 12px 15px;
            text-align: left;
            font-size: 16px;
        }

        .catalog-table tbody td,
        .reward-table tbody td {
            padding: 10px 15px;
            border-bottom: 1px solid #ddd;
            color: #333;
            font-size: 15px;
        }

        .catalog-table tbody tr:last-child td,
        .reward-table tbody tr:last-child td {
            border-bottom: none;
        }

        .catalog-table tbody tr:nth-child(even),
        .reward-table tbody tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        .action-button {
            display: inline-block;
            padding: 8px 12px;
            border-radius: 4px;
            text-decoration: none;
            font-size: 14px;
            font-weight: bold;
            color: white;
            transition: background-color 0.3s ease;
            margin-right: 5px;
        }

        .modify-button {
            background-color: #007bff;
        }

        .modify-button:hover {
            background-color: #0056b3;
        }

        .delete-button {
            background-color: #dc3545;
        }

        .delete-button:hover {
            background-color: #c82333;
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
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <i class="fas fa-edit icon"></i>
            <h1>MODIFICAR CATÁLOGO</h1>
        </div>

        <h2>Editar nombre del catálogo</h2>

        <?php if (isset($error)) {
            echo '<p style="color:red; font-weight:bold;">' . htmlspecialchars($error) . '</p>';
        } ?>

        <form action="" method="POST" class="add-form">
            <input type="text" name="nombre_catalogo" value="<?php echo htmlspecialchars($catalogo['nombreCatalogo']); ?>" required>
            <input type="submit" value="Guardar Cambios">
        </form>

        <div class="back-button-container">
            <a href="catalogos.php" class="back-button">Volver</a>
        </div>
    </div>
</body>

</html>
