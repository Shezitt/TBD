<?php
    require_once("conexion.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Puntos de reciclaje cercanos</title>
    <style>
        .container {
            max-width: 1000px;
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
            <h1>PUNTOS DE RECICLAJE CERCANOS</h1>
        </div>

        <h2>Ingresa tu ubicación</h2>

        <form method="POST">
            <input name="latitud" type="text" placeholder="Latitud">
            <br>
            <input name="longitud" type="text" placeholder="Longitud">
            <br>
            <input name="ubicacion" type="submit" value="Buscar">
        </form>

        <br>

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
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            if (isset($_POST['ubicacion'])) {
                                $latitud = (double) $_POST['latitud'];
                                $longitud = (double) $_POST['longitud'];
                                $stmt = $conn->prepare("CALL sp_getPuntosReciclajeCercanos(?, ?);");
                                $stmt->bind_param("dd", $latitud, $longitud);
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
                                    $id = $fila['idPunto'];
                                    echo "<td><a href='registrarReciclaje.php?id=$id'>Reciclar</a></td>";
                                    echo "</tr>";
                                }
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