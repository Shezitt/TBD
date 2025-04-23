<?php
    require_once("../conexion.php");
    $stmt = $conn->prepare("CALL sp_getCatalogos();");
    $stmt->execute();
    $catalogo = $stmt->get_result();
    $stmt->close();        

    $stmt = $conn->prepare("SELECT * FROM Recompensa WHERE activo='1';");
    $stmt->execute();
    $recompensa = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestionar catálogos</title>
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
            <h1>GESTIONAR CATALOGOS Y RECOMPENSAS</h1>
        </div>

        <div class="cuerpo">
        
            <h2>Agregar nuevo catálogo</h2>

            <form action="">
                <input type="text" placeholder="Nombre">
                <input type="submit">
            </form>

            <table border>
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th colspan="2">Opciones</th>
                    </tr>
                </thead>
                <tbody>
                    
                    <?php
                        while ($fila = $catalogo->fetch_assoc()) {
                            echo '<tr>';
                            echo '<td>' . $fila['nombreCatalogo'] . '</td>';
                            echo '<td><a href="">Modificar</a></td>';
                            echo '<td><a href="">Eliminar</a></td>';
                            echo '</tr>';
                        }
                    ?>

                </tbody>
            </table>

            <h2>Agregar nueva recompensa</h2>

            <form action="">
                <input type="text" placeholder="Nombre">
                <input type="number" placeholder="Puntos necesarios">
                <input type="number" placeholder="Nivel requerido">
                <select>
                    <?php
                        $stmt = $conn->prepare("SELECT * FROM Catalogo WHERE activo='1';");
                        $stmt->execute();
                        $catalogo2 = $stmt->get_result();
                        $stmt->close();

                        while ($fila = $catalogo2->fetch_assoc()) {
                            $idCatalogo = $fila['idCatalogo'];
                            echo "<option value='$idCatalogo'>" . $fila['nombreCatalogo'] . "</option>";
                        }

                    ?>
                </select>
                <input type="submit">
            </form>

            <table border>
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Puntos necesarios</th>
                        <th>Nivel requerido</th>
                        <th>Catálogo</th>
                        <th colspan="2">Opciones</th>
                    </tr>
                </thead>
                <tbody>
                    
                    <?php
                        while ($fila = $recompensa->fetch_assoc()) {
                            $idCatalogo = $fila['idCatalogo'];

                            echo '<tr>';
                            echo '<td>' . $fila['nombre'] . '</td>';
                            echo '<td>' . $fila['puntosNecesarios'] . '</td>';
                            echo '<td>' . $fila['nivelRequerido'] . '</td>';

                            $stmt = $conn->prepare("SELECT nombreCatalogo FROM Catalogo WHERE idCatalogo='$idCatalogo';");
                            $stmt->execute();
                            $resultado = $stmt->get_result();
                            $stmt->close();
                            $nombreCatalogo = $resultado->fetch_assoc()['nombreCatalogo'];
                            echo '<td>' . $nombreCatalogo . '</td>';
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