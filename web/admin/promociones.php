<?php
    require_once("../conexion.php");
    $stmt = $conn->prepare("SELECT * FROM Promocion WHERE activo='1';");
    $stmt->execute();
    $resultado = $stmt->get_result();
    $stmt->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestionar promociones</title>
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
            <h1>GESTIONAR PROMOCIONES</h1>
        </div>

        <div class="cuerpo">
        
            <h2>Agregar nueva promoción</h2>

            <form action="">
                <input type="text" placeholder="Nombre">
                <input type="number" placeholder="Multiplicador">
                <input type="number" placeholder="Nivel requerido"> 
                <br>
                <label for="">Fecha inicio</label>
                <input type="date">
                <label for="">Fecha fin</label>
                <input type="date">
                <input type="submit">
            </form>

            <table border>
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Multiplicador</th>
                        <th>Nivel requerido</th>
                        <th>Fecha inicio</th>
                        <th>Fecha fin</th>
                        <th colspan="2">Opciones</th>
                    </tr>
                </thead>
                <tbody>
                    
                    <?php
                        while ($fila = $resultado->fetch_assoc()) {
                            echo '<tr>';
                            echo '<td>' . $fila['nombre'] . '</td>';
                            echo '<td>' . $fila['multiplicador'] . '</td>';
                            echo '<td>' . $fila['nivelRequerido'] . '</td>';
                            echo '<td>' . $fila['fechaInicio'] . '</td>';
                            echo '<td>' . $fila['fechaFin'] . '</td>';
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