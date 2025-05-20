<?php
    session_start();

    $idUsuario = $_SESSION['idUsuario'];
    require_once("conexion.php");

    $idPunto = $_GET['id'];

    $stmt = $conn->prepare("SELECT * FROM Punto_Reciclaje WHERE idPunto=? ;");
    $stmt->bind_param("i", $idPunto);
    $stmt->execute();
    $punto = $stmt->get_result()->fetch_assoc();
    $stmt->close();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar reciclaje</title>
    <style>
        .container {
            max-width: 800px;
            margin: auto;
        }
        .container .header {
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>REGISTRAR RECICLAJE</h1>
        </div>

        <div class="cuerpo">

            <p>
                Punto ID: <?php echo $idPunto; ?>
            </p>
            <p>
                Nombre del punto: <?php echo $punto['nombre']; ?>
            </p>

            <h2>Selecciona tus materiales para reciclar</h2>

            <form method="POST">
                <select name="material" id="">
                    <?php

                        $stmt = $conn->prepare("CALL sp_getMaterialesPuntoReciclaje(?);");
                        $stmt->bind_param("i", $idPunto);
                        $stmt->execute();
                        $materiales = $stmt->get_result();
                        $stmt->close();

                        while ($fila = $materiales->fetch_assoc()) {
                            $idMaterial = $fila['idMaterial'];
                            echo "<option value='$idMaterial'>" . $fila['nombre'] . "</option>";
                        }

                    ?>
                    
                </select> 
                <input name="cantidad" type="text" placeholder="Cantidad Kg"> <br>
                <input name="reciclar" type="submit" value="Reciclar">
            </form>

            <?php

                if (isset($_POST['reciclar'])) {
                    $idMaterial = $_POST['material'];
                    $cantidad = (double) $_POST['cantidad'];

                    $stmt = $conn->prepare("CALL sp_registrarReciclaje(?, ?, ?, ?, @p_puntos_ganados);");
                    $stmt->bind_param("iiid", $idUsuario, $idMaterial, $idPunto, $cantidad);
                    $estado = $stmt->execute();
                    $stmt->close();

                    
                    if ($estado) {
                        $resultado = $conn->query("SELECT @p_puntos_ganados AS puntosGanados");
                        $fila = $resultado->fetch_assoc();
                        echo "<h3>Gracias por tu reciclaje!!</h3>";
                        echo "Recibiste <b>" .$fila['puntosGanados'] . "</b> puntos!!";
                    } else {
                        echo "<h3>Algo salió mal</h3>";
                    }

                }

            ?>

        </div>

        <a href="puntosReciclajeCercanos.php">Volver</a>
        
    </div>  
</body>
</html>