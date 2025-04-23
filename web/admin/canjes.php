<?php
    require_once("../conexion.php");
    $stmt = $conn->prepare("CALL sp_getCanjesPendientes();");
    $stmt->execute();
    $resultado = $stmt->get_result();
    $stmt->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestionar canjes pendientes</title>
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
            <h1>GESTIONAR CANJES PENDIENTES</h1>
        </div>

        <div class="cuerpo">
        
            <h2>Completar canjes</h2>

            <table border>
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
                    
                        while ($fila = $resultado->fetch_assoc()) {
                            echo '<tr>';
                            echo '<td>' . $fila['nombreUsuario'] . '</td>';
                            echo '<td>' . $fila['nivelUsuario'] . '</td>';
                            echo '<td>' . $fila['nombreRecompensa'] . '</td>';
                            echo '<td>' . $fila['fecha'] . '</td>';
                            echo '<td><a href="">Completar</a></td>';
                            echo '</tr>';
                        }
                    ?>

                </tbody>
            </table>

        </div>

        
        
    </div>  
</body>
</html>