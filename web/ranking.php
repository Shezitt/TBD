<?php
session_start();
require_once("conexion.php");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ránking de usuarios</title>
    <style>
        /* (el mismo estilo que antes) */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f8fa;
            margin: 0;
            padding: 20px;
        }

        .container {
            max-width: 900px;
            margin: auto;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .header h1 {
            color: #0a2d3c;
        }

        .btn-verde {
            background-color: #98eb9a;
            color: #0a2d3c;
            font-weight: bold;
            padding: 10px 20px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            text-align: center;
        }

        thead {
            background-color: #032d3c;
            color: white;
        }

        tbody tr:nth-child(even) {
            background-color: #e0e0e0;
        }

        td,
        th {
            padding: 10px;
            border: 1px solid #ccc;
        }

        .texto-no {
            color: #555;
        }

        .btn {
            margin: 1em;
            background-color: #001f2d;
            color: white;
            padding: 10px 30px;
            text-decoration: none;
            font-weight: bold;
            border: none;
            cursor: pointer;
            font-size: 16px;
        }

        .btn:hover {
            background-color: #003049;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>RÁNKING DE USUARIOS</h1>
        </div>

        <div class="cuerpo">

            <table border>
                <thead>
                    <tr>
                        <th>Nº</th>
                        <th>Nombre de usuario</th>
                        <th>Nombre</th>
                        <th>Nivel</th>
                        <th>Total de puntos</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $stmt = $conn->prepare("CALL getRankingPuntos();");
                    $stmt->execute();
                    $resultado = $stmt->get_result();

                    $cnt = 1;

                    while ($fila = $resultado->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $cnt++ . "</td>";
                        echo "<td>" . $fila['username'] . "</td>";
                        echo "<td>" . $fila['nombre'] . "</td>";
                        echo "<td>" . $fila['nivel'] . "</td>";
                        echo "<td>" . $fila['puntosTotal'] . "</td>";
                        echo "</tr>";
                    }
                    ?>
                </tbody>
            </table>

        </div>


        <div class="button-container">
            <button class="btn" onclick="history.back()">Anterior</button>
        </div>
    </div>

</body>

</html>