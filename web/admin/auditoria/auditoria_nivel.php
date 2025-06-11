<?php
session_start();

if (!in_array("dashboard", $_SESSION['permisos']) or !in_array("dashboard_historial", $_SESSION['permisos'])) {
    header("Location: index.php");
    exit();
}
require_once("../../conexion.php");

$error = "";
$mensaje = "";

// Ejecutar reverseSQL si se envió
if (isset($_POST['aplicar_reverse']) && isset($_POST['reverse_sql'])) {
    $reverse_sql = $_POST['reverse_sql'];

    if ($conn->query($reverse_sql) === TRUE) {
        $mensaje = "Consulta reverseSQL ejecutada correctamente.";
    } else {
        $error = "Error al ejecutar reverseSQL: " . $conn->error;
    }
}

// Obtener registros de auditoría
$resultado = $conn->query("SELECT fecha, executedSQL, reverseSQL FROM Auditoria_Nivel ORDER BY idAuditoria DESC");

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Auditoría Nivel</title>
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
            width: 95%;
            max-width: 1200px;
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
            color: #2980b9;
            margin-right: 15px;
        }

        .header h1 {
            font-size: 28px;
            color: #333;
            margin: 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        thead th {
            background-color: #2980b9;
            color: white;
            padding: 12px 15px;
            text-align: left;
        }

        tbody td {
            padding: 10px 15px;
            border-bottom: 1px solid #ddd;
            vertical-align: top;
        }

        tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .button {
            background-color: #c0392b;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
        }

        .button:hover {
            background-color: #922b21;
        }

        .message {
            color: green;
            margin-bottom: 15px;
        }

        .error {
            color: red;
            margin-bottom: 15px;
        }

        .sql-text {
            font-family: monospace;
            font-size: 13px;
            white-space: pre-wrap;
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
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <i class="fas fa-database icon"></i>
            <h1>AUDITORÍA DE NIVEL</h1>
        </div>

        <?php if ($mensaje) echo "<div class='message'>$mensaje</div>"; ?>
        <?php if ($error) echo "<div class='error'>$error</div>"; ?>

        <?php if ($resultado && $resultado->num_rows > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Consulta Ejecutada</th>
                        <th>Consulta Reverse</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($fila = $resultado->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($fila['fecha']); ?></td>
                            <td class="sql-text"><?php echo htmlspecialchars($fila['executedSQL']); ?></td>
                            <td class="sql-text"><?php echo htmlspecialchars($fila['reverseSQL']); ?></td>
                            <td>
                                <form method="POST" onsubmit="return confirm('¿Estás seguro de ejecutar la consulta reverse?');">
                                    <input type="hidden" name="reverse_sql" value="<?php echo htmlspecialchars($fila['reverseSQL']); ?>">
                                    <button type="submit" name="aplicar_reverse" class="button">Aplicar Reverse</button>
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No hay registros de auditoría disponibles.</p>
        <?php endif; ?>

        <div class="back-button-container">
            <a href="../../panelAdministrativo.php" class="back-button">Anterior</a>
        </div>
    </div>
</body>

</html>
