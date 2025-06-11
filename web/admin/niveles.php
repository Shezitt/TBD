<?php
session_start();

if (!in_array("dashboard", $_SESSION['permisos']) or !in_array("dashboard_gestion", $_SESSION['permisos'])) {
    header("Location: index.php");
    exit();
}

require_once("../conexion.php");

$stmt = $conn->prepare("SELECT * FROM Nivel;");
if ($stmt) {
    $stmt->execute();
    $resultado = $stmt->get_result();
    $stmt->close();
} else {
    echo "Error al preparar la consulta: " . $conn->error;
    $resultado = false;
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Gestionar Niveles</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
    <style>
        /* Reutilizo estilos del ejemplo material */
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

        .add-level-form {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            margin-bottom: 30px;
            align-items: flex-end;
        }

        .add-level-form input[type="number"],
        .add-level-form input[type="text"] {
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

        .add-level-form input[type="submit"] {
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

        .add-level-form input[type="submit"]:hover {
            background-color: #0056b3;
        }

        .table-container {
            border: 2px solid #007bff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .levels-table {
            width: 100%;
            border-collapse: collapse;
            margin: 0;
        }

        .levels-table thead th {
            background-color: #007bff;
            color: white;
            padding: 12px 15px;
            text-align: left;
            font-size: 16px;
        }

        .levels-table tbody td {
            padding: 10px 15px;
            border-bottom: 1px solid #ddd;
            color: #333;
            font-size: 15px;
        }

        .levels-table tbody tr:last-child td {
            border-bottom: none;
        }

        .levels-table tbody tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        .levels-table .action-button {
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

        .levels-table .modify-button {
            background-color: #007bff;
        }

        .levels-table .modify-button:hover {
            background-color: #0056b3;
        }

        .levels-table .delete-button {
            background-color: #dc3545;
        }

        .levels-table .delete-button:hover {
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
            <i class="fas fa-layer-group icon"></i>
            <h1>GESTIONAR NIVELES</h1>
        </div>

        <h2>AGREGAR NUEVO NIVEL</h2>

        <form action="" method="POST" class="add-level-form">
            <input type="number" name="nivel" placeholder="Nivel" step="1" min="1" required />
            <input type="text" name="nombre" placeholder="Nombre" required />
            <input type="number" name="puntos_totales_necesarios" placeholder="Puntos Totales Necesarios" step="0.01" min="0"
                required />
            <input type="submit" name="agregar_nivel" value="Enviar" />
        </form>

        <div class="table-container">
            <table class="levels-table">
                <thead>
                    <tr>
                        <th>Nivel</th>
                        <th>Nombre</th>
                        <th>Puntos Totales Necesarios</th>
                        <th colspan="2">Opciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($resultado && $resultado->num_rows > 0) {
                        while ($fila = $resultado->fetch_assoc()) {
                            echo '<tr>';
                            echo '<td>' . htmlspecialchars($fila['nivel']) . '</td>';
                            echo '<td>' . htmlspecialchars($fila['nombre']) . '</td>';
                            echo '<td>' . htmlspecialchars($fila['puntosTotalesNecesarios']) . '</td>';
                            echo '<td><a href="modificar_nivel.php?id=' . $fila['idNivel'] . '" class="action-button modify-button">Modificar</a></td>';
                            echo '<td><a href="eliminar_nivel.php?id=' . $fila['idNivel'] . '" class="action-button delete-button">Eliminar</a></td>';
                            echo '</tr>';
                        }
                    } else {
                        echo '<tr><td colspan="5">No hay niveles activos.</td></tr>';
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <div class="back-button-container">
            <a href="../panelAdministrativo.php" class="back-button">Anterior</a>
        </div>
    </div>
</body>

</html>

<?php
if (isset($_POST['agregar_nivel'])) {
    $nivel = $_POST['nivel'];
    $nombre = $_POST['nombre'];
    $puntos_totales_necesarios = $_POST['puntos_totales_necesarios'];

    $stmt = $conn->prepare("CALL sp_nuevoNivel(?, ?, ?);");
    $stmt->bind_param("isd", $nivel, $nombre, $puntos_totales_necesarios);
    $stmt->execute();
    $stmt->close();

    // Refrescar página para ver cambios
    echo "<script>window.location.href = window.location.pathname;</script>";
}
?>
