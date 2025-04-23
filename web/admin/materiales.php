<?php
    require_once("../conexion.php");
    $stmt = $conn->prepare("SELECT * FROM Material WHERE activo='1';");
    $stmt->execute();
    $resultado = $stmt->get_result();
    $stmt->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestionar materiales</title>
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
            <h1>GESTIONAR MATERIALES</h1>
        </div>

        <div class="cuerpo">
        
            <h2>Agregar nuevo material</h2>

            <form action="">
                <input type="text" placeholder="Nombre">
                <input type="number" placeholder="Coeficiente puntos">
                <input type="number" placeholder="Coeficiente impacto CO2">
                <input type="submit">
            </form>

            <table border>
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Coeficiente puntos</th>
                        <th>Coeficiente impacto CO2</th>
                        <th colspan="2">Opciones</th>
                    </tr>
                </thead>
                <tbody>
                    
                    <?php
                        while ($fila = $resultado->fetch_assoc()) {
                            echo '<tr>';
                            echo '<td>' . $fila['nombre'] . '</td>';
                            echo '<td>' . $fila['coeficientePuntos'] . '</td>';
                            echo '<td>' . $fila['coeficienteCO2'] . '</td>';
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