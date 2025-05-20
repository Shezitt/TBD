<?php
    require_once("conexion.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Puntos de reciclaje</title>
    <style>
        .container {
            max-width: 800px;
            margin: auto;
        }
        .container .header {
            text-align: center;
        }
        .container .cuerpo {
            display: flex;
            flex-direction: row;
        }
        table {
            margin: 1em;
            border-collapse: collapse;
        }
        table td {
            padding: 0.6em;
        }

        
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>PUNTOS DE RECICLAJE</h1>
        </div>

        <div class="cuerpo">

            <div class="mapa-contenedor">
                <img width="300px" src="images/mapa.png">
            </div>

            <div class="tabla-contenedor">
                <table border>
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Latitud</th>
                            <th>Longitud</th>
                            <th>Apertura</th>
                            <th>Cierre</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $stmt = $conn->prepare("CALL sp_getPuntosReciclaje();");
                            $stmt->execute();
                            $resultado = $stmt->get_result();
                            $stmt->close();

                            while ($fila = $resultado->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td>" . $fila['nombre'] . "</td>";
                                echo "<td>" . $fila['latitud'] . "</td>";
                                echo "<td>" . $fila['longitud'] . "</td>";
                                echo "<td>" . $fila['apertura'] . "</td>";
                                echo "<td>" . $fila['cierre'] . "</td>";
                                echo "</tr>";
                            }

                        ?>
                    </tbody>
                </table>
            </div>
            
            
            
        </div>

        <a href="index.php">Volver</a>
        
    </div>  
</body>
</html>