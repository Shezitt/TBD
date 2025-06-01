<?php
    require_once("conexion.php");
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Puntos de Reciclaje</title>

    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />

    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f6f8;
            color: #333;
        }

        .container {
            max-width: 1100px;
            margin: 40px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 12px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
        }

        .header h1 {
            font-size: 2.5em;
            color: #27ae60;
        }

        .cuerpo {
            display: flex;
            flex-wrap: wrap;
            gap: 30px;
            justify-content: center;
        }

        .mapa-contenedor {
            flex: 1 1 400px;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        #mapa {
            width: 100%;
            max-width: 350px;
            height: 350px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
        }

        .tabla-contenedor {
            flex: 2 1 500px;
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background-color: #fff;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        table thead {
            background-color: #27ae60;
            color: #fff;
        }

        table th, table td {
            padding: 14px 18px;
            text-align: center;
        }

        table tbody tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        table tbody tr:hover {
            background-color: #dff0d8;
        }

        .boton-link {
            display: inline-block;
            margin-top: 30px;
            padding: 12px 24px;
            background-color: #27ae60;
            color: #fff;
            text-decoration: none;
            border-radius: 8px;
            transition: background-color 0.3s;
        }

        .boton-link:hover {
            background-color: #219150;
        }

        @media(max-width: 768px) {
            .cuerpo {
                flex-direction: column;
                align-items: center;
            }

            .tabla-contenedor {
                width: 100%;
            }
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
                <div id="mapa"></div>
            </div>

            <div class="tabla-contenedor">
                <table>
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
                            $puntos = [];

                            while ($fila = $resultado->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td>" . htmlspecialchars($fila['nombre']) . "</td>";
                                echo "<td>" . htmlspecialchars($fila['latitud']) . "</td>";
                                echo "<td>" . htmlspecialchars($fila['longitud']) . "</td>";
                                echo "<td>" . htmlspecialchars($fila['apertura']) . "</td>";
                                echo "<td>" . htmlspecialchars($fila['cierre']) . "</td>";
                                echo "</tr>";

                                // Guardamos los datos para pasarlos al JS
                                $puntos[] = $fila;
                            }

                            $stmt->close();
                        ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div style="text-align: center;">
            <a class="boton-link" href="verPuntosReciclaje.php">Volver</a>
        </div>
    </div>

    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

    <script>
        var mapa = L.map('mapa').setView([-17.3820, -66.1596], 13);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        }).addTo(mapa);

        // Pasamos los puntos desde PHP a JS
        var puntos = <?php echo json_encode($puntos); ?>;

        puntos.forEach(function(punto) {
            L.marker([punto.latitud, punto.longitud]).addTo(mapa)
                .bindPopup("<b>" + punto.nombre + "</b><br>Apertura: " + punto.apertura + "<br>Cierre: " + punto.cierre);
        });
    </script>

</body>
</html>
