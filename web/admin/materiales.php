<?php

require_once("../conexion.php");
$stmt = $conn->prepare("SELECT * FROM Material WHERE activo = 1;");

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
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestionar Materiales</title>
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
            /* Color verde para el ícono de reciclaje */
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
            /* Color azul para "AGREGARR NUEVO MATERIAL" */
            margin-top: 0;
            margin-bottom: 20px;
        }

        .add-material-form {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            margin-bottom: 30px;
            align-items: flex-end;
        }

        .add-material-form input[type="text"],
        .add-material-form input[type="number"] {
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

        .add-material-form input[type="submit"] {
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

        .add-material-form input[type="submit"]:hover {
            background-color: #0056b3;
        }

        .table-container {
            border: 2px solid #007bff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .materials-table {
            width: 100%;
            border-collapse: collapse;
            margin: 0;
        }

        .materials-table thead th {
            background-color: #007bff;
            /* Fondo azul para el encabezado de la tabla */
            color: white;
            padding: 12px 15px;
            text-align: left;
            font-size: 16px;
        }

        .materials-table tbody td {
            padding: 10px 15px;
            border-bottom: 1px solid #ddd;
            color: #333;
            font-size: 15px;
        }

        .materials-table tbody tr:last-child td {
            border-bottom: none;
        }

        .materials-table tbody tr:nth-child(even) {
            background-color: #f2f2f2;
            /* Color de fondo para filas pares */
        }

        .materials-table .action-button {
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

        .materials-table .modify-button {
            background-color: #007bff;
            /* Azul para modificar */
        }

        .materials-table .modify-button:hover {
            background-color: #0056b3;
        }

        .materials-table .delete-button {
            background-color: #dc3545;
            /* Rojo para eliminar */
        }

        .materials-table .delete-button:hover {
            background-color: #c82333;
        }

        .back-button-container {
            margin-top: 30px;
            text-align: left;
        }

        .back-button {
            display: inline-block;
            background-color: #34495e;
            /* Gris oscuro similar al panel admin */
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
            <i class="fas fa-recycle icon"></i>
            <h1>GESTIONAR MATERIALES</h1>
        </div>

        <h2>AGREGAR NUEVO MATERIAL</h2>

        <form action="" method="POST" class="add-material-form">
            <input type="text" name="nombre" placeholder="Nombre" required>
            <input type="number" name="coeficiente_puntos" placeholder="Coeficiente Puntos" step="0.01" required>
            <input type="number" name="coeficiente_impacto_co2" placeholder="Coeficiente Impacto CO2" step="0.01"
                required>
            <input type="submit" value="Enviar">
        </form>

        <div class="table-container">
            <table class="materials-table">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Coeficiente Puntos</th>
                        <th>Coeficiente Impacto CO2</th>
                        <th colspan="2">Opciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($resultado && $resultado->num_rows > 0) {
                        while ($fila = $resultado->fetch_assoc()) {
                            echo '<tr>';
                            echo '<td>' . htmlspecialchars($fila['nombre']) . '</td>';
                            echo '<td>' . htmlspecialchars($fila['coeficientePuntos']) . '</td>';
                            echo '<td>' . htmlspecialchars($fila['coeficienteCO2']) . '</td>';
                            echo '<td><a href="modificar_material.php?id=' . htmlspecialchars($fila['id'] ?? '') . '" class="action-button modify-button">Modificar</a></td>';
                            echo '<td><a href="eliminar_material.php?id=' . htmlspecialchars($fila['id'] ?? '') . '" class="action-button delete-button">Eliminar</a></td>';
                            echo '</tr>';
                        }
                    } else {
                        echo '<tr><td colspan="5">No hay materiales activos.</td></tr>';
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

if (isset($conn)) {
    $conn->close();
}
?>