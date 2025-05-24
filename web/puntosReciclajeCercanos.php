<?php
    require_once("conexion.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Puntos de reciclaje cercanos</title>

    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />

    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f0f4f8;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 1000px;
            margin: 30px auto;
            padding: 20px;
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0 0 15px rgba(0,0,0,0.08);
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .header h1 {
            color: #2c3e50;
            font-size: 32px;
            margin: 0;
        }
        h2 {
            color: #34495e;
        }
        form {
            display: flex;
            flex-direction: column;
            gap: 12px;
            max-width: 300px;
            margin-bottom: 30px;
        }
        form input[type="text"] {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 8px;
            outline: none;
            transition: 0.3s;
        }
        form input[type="text"]:focus {
            border-color: #3498db;
        }
        form input[type="submit"] {
            background: #27ae60;
            color: #fff;
            padding: 12px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: 0.3s;
        }
        form input[type="submit"]:hover {
            background: #219150;
        }
        .cuerpo {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
            justify-content: center;
        }
        .mapa-contenedor {
            flex: 1 1 400px;
        }
        #map {
            width: 100%;
            height: 400px;
            border-radius: 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            background: #ecf0f1;
            border-radius: 8px;
            overflow: hidden;
        }
        table thead {
            background: #3498db;
            color: #fff;
        }
        table th, table td {
            padding: 12px 16px;
            text-align: center;
            border-bottom: 1px solid #bdc3c7;
        }
        table tr:hover {
            background-color: #d6eaf8;
        }
        table td a {
            background: #27ae60;
            color: #fff;
            padding: 6px 12px;
            border-radius: 6px;
            text-decoration: none;
            transition: 0.3s;
        }
        table td a:hover {
            background: #219150;
        }
        a.volver {
            display: inline-block;
            margin-top: 30px;
            padding: 12px 24px;
            background-color: #27ae60;
            color: #fff;
            text-decoration: none;
            border-radius: 8px;
            transition: background-color 0.3s;
        }

        a.volver:hover {
            background-color: #219150;
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
            <input name="latitud" type="text" placeholder="Latitud" required>
            <input name="longitud" type="text" placeholder="Longitud" required>
            <input name="ubicacion" type="submit" value="Buscar">
        </form>

        <div class="cuerpo">

            <div class="mapa-contenedor">
                <div id="map"></div>
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
                            <th>Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $puntos = [];
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
                                    echo "<td>" . htmlspecialchars($fila['nombre']) . "</td>";
                                    echo "<td>" . $fila['latitud'] . "</td>";
                                    echo "<td>" . $fila['longitud'] . "</td>";
                                    echo "<td>" . $fila['apertura'] . "</td>";
                                    echo "<td>" . $fila['cierre'] . "</td>";
                                    $id = $fila['idPunto'];
                                    echo "<td><a href='registrarReciclaje.php?id=$id'>Reciclar</a></td>";
                                    echo "</tr>";

                                    // Almacenar puntos para el mapa
                                    $puntos[] = $fila;
                                }
                            } else {
                                echo "<tr><td colspan='6'>Ingresa tu ubicación para buscar puntos cercanos.</td></tr>";
                            }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>

        <a class="volver" href="index.php">Volver</a>
    </div>  

    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

    <script>
        // Crear mapa con posición por defecto
        var map = L.map('map').setView([-17.3820, -66.1596], 13);

        // Cargar tiles desde OpenStreetMap
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        <?php if (isset($_POST['ubicacion'])): ?>
            // Posición del usuario
            var userLat = <?php echo $latitud; ?>;
            var userLng = <?php echo $longitud; ?>;
            L.marker([userLat, userLng]).addTo(map)
                .bindPopup("Tu ubicación").openPopup();

            // Centrar mapa en el usuario
            map.setView([userLat, userLng], 14);

            // Puntos de reciclaje
            var puntos = <?php echo json_encode($puntos); ?>;
            puntos.forEach(function(punto) {
            L.marker([punto.latitud, punto.longitud]).addTo(map)
                .bindPopup("<b>" + punto.nombre + "</b><br>Apertura: " + punto.apertura + "<br>Cierre: " + punto.cierre);
        });
        <?php endif; ?>
    </script>
</body>
</html>
