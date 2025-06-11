<?php

session_start();

require_once("../conexion.php");
if (!in_array("dashboard", $_SESSION['permisos']) or !in_array("dashboard_gestion", $_SESSION['permisos'])) {
    header("Location: index.php");
    exit();
}
$stmt = $conn->prepare("CALL sp_getCanjesPendientes();");
$stmt->execute();
$resultado = $stmt->get_result();
$stmt->close();
?>
<!DOCTYPE html>
<html lang="es"> 
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Gestionar Canjes Pendientes</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
    <style>
        /* Copié exactamente los estilos de la primera página */
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

        .table-container {
            border: 2px solid #007bff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 0;
        }

        thead th {
            background-color: #007bff;
            color: white;
            padding: 12px 15px;
            text-align: left;
            font-size: 16px;
        }

        tbody td {
            padding: 10px 15px;
            border-bottom: 1px solid #ddd;
            color: #333;
            font-size: 15px;
        }

        tbody tr:last-child td {
            border-bottom: none;
        }

        tbody tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        a.action-button {
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

        a.complete-button {
            background-color: #28a745; /* Verde para completar */
        }

        a.complete-button:hover {
            background-color: #1e7e34;
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
            <i class="fas fa-hand-holding-heart icon"></i>
            <h1>GESTIONAR CANJES PENDIENTES</h1>
        </div>

        <h2>Completar canjes</h2>

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Nombre usuario</th>
                        <th>Nivel usuario</th>
                        <th>Nombre recompensa</th>
                        <th>Fecha canje</th>
                        <th>Completar</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($resultado && $resultado->num_rows > 0) {
                        while ($fila = $resultado->fetch_assoc()) {
                            echo '<tr>';
                            echo '<td>' . htmlspecialchars($fila['nombreUsuario']) . '</td>';
                            echo '<td>' . htmlspecialchars($fila['nivelUsuario']) . '</td>';
                            echo '<td>' . htmlspecialchars($fila['nombreRecompensa']) . '</td>';
                            echo '<td>' . htmlspecialchars($fila['fecha']) . '</td>';
                            echo '<td><a href="completar_canje.php?id=' . htmlspecialchars($fila['idCanje']) . '" class="action-button complete-button">Completar</a></td>';
                            echo '</tr>';
                        }
                    } else {
                        echo '<tr><td colspan="5">No hay canjes pendientes.</td></tr>';
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
