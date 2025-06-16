<?php
session_start();

if (!in_array("dashboard", $_SESSION['permisos']) or !in_array("dashboard_reportes", $_SESSION['permisos'])) {
    header("Location: index.php");
    exit();
}

require_once("../../conexion.php");

$reporte_resultado = false;
$error = "";

if (isset($_POST['generar_reporte'])) {
    $fecha_inicio = $_POST['fecha_inicio'];
    $fecha_fin = $_POST['fecha_fin'];

    if ($fecha_inicio && $fecha_fin) {
        $stmt = $conn->prepare("CALL sp_reporteMaterialesImpacto(?, ?)");
        if ($stmt) {
            $stmt->bind_param("ss", $fecha_inicio, $fecha_fin);
            $stmt->execute();
            $reporte_resultado = $stmt->get_result();
        } else {
            $error = "Error al preparar la consulta: " . $conn->error;
        }
    } else {
        $error = "Por favor, selecciona ambas fechas.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Reporte Impacto Ambiental</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        /* mismos estilos que la plantilla anterior */
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

        form {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            margin-bottom: 30px;
            align-items: flex-end;
        }

        form label {
            font-size: 14px;
            color: #555;
        }

        form input[type="date"],
        form input[type="submit"] {
            padding: 10px 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }

        form input[type="submit"] {
            background-color: #007bff;
            color: white;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        form input[type="submit"]:hover {
            background-color: #0056b3;
        }

        .table-container {
            border: 2px solid #007bff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        thead th {
            background-color: #007bff;
            color: white;
            padding: 12px 15px;
            text-align: left;
        }

        tbody td {
            padding: 10px 15px;
            border-bottom: 1px solid #ddd;
        }

        tbody tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        .back-button-container {
            margin-top: 30px;
        }

        .back-button {
            display: inline-block;
            background-color: #34495e;
            color: white;
            padding: 12px 25px;
            border-radius: 5px;
            text-decoration: none;
            font-weight: bold;
            transition: background-color 0.3s ease;
        }

        .back-button:hover {
            background-color: #2c3e50;
        }

        .error {
            color: red;
            margin-bottom: 15px;
        }

        canvas {
  background: #fff;
  box-shadow: 0 4px 12px rgba(0,0,0,0.1);
  border-radius: 12px;
  padding: 10px;
}

    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <i class="fas fa-leaf icon"></i>
            <h1>REPORTE IMPACTO AMBIENTAL</h1>
        </div>

        <form method="POST">
            <div>
                <label for="fecha_inicio">Desde:</label>
                <input type="date" name="fecha_inicio" value="2025-01-01" required>
            </div>

            <div>
                <label for="fecha_fin">Hasta:</label>
                <input type="date" name="fecha_fin" value="<?php echo date('Y-m-d'); ?>" required>
            </div>

            <input type="submit" name="generar_reporte" value="Generar Reporte">
        </form>

        <?php if ($error) echo "<div class='error'>$error</div>"; ?>

        <?php if ($reporte_resultado && $reporte_resultado->num_rows > 0): ?>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Nº</th>
                            <th>Material</th>
                            <th>Total Reciclado (kg)</th>
                            <th>Total Puntos Generados</th>
                            <th>CO₂ Reducido (kg)</th>
                            <th>Agua Ahorrada (L)</th>
                            <th>Energía Ahorrada (kWh)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $contador = 1; 
                        
                        $suma_kg = 0;
                        $suma_puntos = 0;
                        $suma_co2 = 0;
                        $suma_agua = 0;
                        $suma_energia = 0;
                        ?>
                        <?php while ($fila = $reporte_resultado->fetch_assoc()): ?>
                            <?php 
                            $suma_kg += $fila['total_reciclado_kg'];
                            $suma_puntos += $fila['total_puntos'];
                            $suma_co2 += $fila['total_co2_reducido'];
                            $suma_agua += $fila['total_agua_reducido'];
                            $suma_energia += $fila['total_energia_reducido'];
                            ?>
                            <tr>
                                <td><?php echo $contador++; ?></td>
                                <td><?php echo htmlspecialchars($fila['material']); ?></td>
                                <td><?php echo number_format($fila['total_reciclado_kg'], 2); ?></td>
                                <td><?php echo number_format($fila['total_puntos'], 2); ?></td>
                                <td><?php echo number_format($fila['total_co2_reducido'], 2); ?></td>
                                <td><?php echo number_format($fila['total_agua_reducido'], 2); ?></td>
                                <td><?php echo number_format($fila['total_energia_reducido'], 2); ?></td>
                            </tr>
                        <?php endwhile; ?>
                        
                        <tr style="font-weight: bold; background-color: #d9edf7;">
                            <td colspan="2">Totales</td>
                            <td><?php echo number_format($suma_kg, 2); ?></td>
                            <td><?php echo number_format($suma_puntos, 2); ?></td>
                            <td><?php echo number_format($suma_co2, 2); ?></td>
                            <td><?php echo number_format($suma_agua, 2); ?></td>
                            <td><?php echo number_format($suma_energia, 2); ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        <?php elseif (isset($_POST['generar_reporte'])): ?>
            <p>No se encontraron registros para ese rango de fechas.</p>
        <?php endif; ?>

        <div class="back-button-container">
            <a href="../../panelAdministrativo.php" class="back-button">Anterior</a>
        </div>
    </div>
</body>

</html>
