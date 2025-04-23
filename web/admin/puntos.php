<?php
    require_once("../conexion.php");
    $stmt = $conn->prepare("SELECT * FROM Punto_Reciclaje WHERE activo='1';");
    $stmt->execute();
    $resultado = $stmt->get_result();
    $stmt->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestionar puntos de reciclaje</title>
    <style>
        .container {
            max-width: 800px;
            margin: auto;
        }
        .container .header {
            text-align: center;
        }
        .container table {
            margin: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>GESTIONAR PUNTOS DE RECICLAJE</h1>
        </div>

        <div class="cuerpo">
        
            <h2>Agregar nuevo punto de reciclaje</h2>

            <form action="">
                <input type="text" placeholder="Nombre">
                <input type="number" placeholder="Latitud">
                <input type="number" placeholder="Longitud">
                <br>
                <label for="">Hora de apertura</label>
                <input type="time">
                <label for="">Hora de cierre</label>
                <input type="time" placeholder="Cierre">
                <input type="submit">
            </form>

            <table border>
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Latitud</th>
                        <th>Longitud</th>
                        <th>Apertura</th>
                        <th>Cierre</th>
                        <th colspan="2">Opciones</th>
                    </tr>
                </thead>
                <tbody>
                    
                    <?php
                        while ($fila = $resultado->fetch_assoc()) {
                            echo '<tr>';
                            echo '<td>' . $fila['nombre'] . '</td>';
                            echo '<td>' . $fila['latitud'] . '</td>';
                            echo '<td>' . $fila['longitud'] . '</td>';
                            echo '<td>' . $fila['apertura'] . '</td>';
                            echo '<td>' . $fila['cierre'] . '</td>';
                            echo '<td><a href="">Modificar</a></td>';
                            echo '<td><a href="">Eliminar</a></td>';
                            echo '</tr>';
                        }
                    ?>

                </tbody>
            </table>

        </div>

        
        
    </div>  
</body>
</html>