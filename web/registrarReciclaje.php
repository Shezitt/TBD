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
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar reciclaje</title>
    <style>
        * {
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
        }

        body {
            background-color: #f1f5f9;
            color: #333;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            min-height: 100vh;
            padding: 30px;
        }

        .container {
            background-color: #ffffff;
            max-width: 500px;
            width: 100%;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }

        .header h1 {
            text-align: center;
            color: #2563eb;
            margin-bottom: 20px;
            font-size: 28px;
        }

        .info {
            margin-bottom: 20px;
        }

        .info p {
            margin-bottom: 8px;
            font-size: 16px;
        }

        h2 {
            margin-bottom: 12px;
            font-size: 20px;
            color: #374151;
        }

        form {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        select, input[type="text"] {
            padding: 10px;
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            font-size: 16px;
            transition: border-color 0.3s;
        }

        select:focus, input[type="text"]:focus {
            border-color: #2563eb;
            outline: none;
        }

        input[type="submit"] {
            background-color: #2563eb;
            color: white;
            padding: 12px;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        input[type="submit"]:hover {
            background-color: #1d4ed8;
        }

        .message {
            margin-top: 20px;
            padding: 15px;
            border-radius: 8px;
            text-align: center;
        }

        .success {
            background-color: #d1fae5;
            color: #065f46;
        }

        .error {
            background-color: #fee2e2;
            color: #991b1b;
        }

        a {
            display: inline-block;
            margin-top: 20px;
            text-decoration: none;
            color: #2563eb;
            font-weight: bold;
            text-align: center;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Registrar Reciclaje</h1>
        </div>

        <div class="info">
            <p><strong>Punto ID:</strong> <?php echo $idPunto; ?></p>
            <p><strong>Nombre del punto:</strong> <?php echo $punto['nombre']; ?></p>
        </div>

        <h2>Selecciona tus materiales</h2>

        <form method="POST">
            <select name="material" required>
                <?php
                    $stmt = $conn->prepare("CALL sp_getMaterialesPuntoReciclaje(?);");
                    $stmt->bind_param("i", $idPunto);
                    $stmt->execute();
                    $materiales = $stmt->get_result();
                    $stmt->close();

                    while ($fila = $materiales->fetch_assoc()) {
                        $idMaterial = $fila['idMaterial'];
                        echo "<option value='$idMaterial'>" . htmlspecialchars($fila['nombre']) . "</option>";
                    }
                ?>
            </select>

            <input name="cantidad" type="text" placeholder="Cantidad en Kg" required pattern="^[0-9]+(\.[0-9]{1,2})?$" title="Introduce una cantidad válida">

            <input name="reciclar" type="submit" value="Reciclar">
        </form>

        <?php
            if (isset($_POST['reciclar'])) {
                $idMaterial = $_POST['material'];
                $cantidad = (double) $_POST['cantidad'];

                $stmt = $conn->prepare("CALL sp_registrarReciclaje(?, ?, ?, ?, @p_puntos_ganados, @p_impacto_co2);");
                $stmt->bind_param("iiid", $idUsuario, $idMaterial, $idPunto, $cantidad);
                $estado = $stmt->execute();
                $stmt->close();

                if ($estado) {
                    $resultado = $conn->query("SELECT @p_puntos_ganados AS puntosGanados, @p_impacto_co2 AS impactoCO2");
                    $fila = $resultado->fetch_assoc();
                    echo "<div class='message success'><h3>¡Gracias por reciclar!</h3>Recibiste <strong>" . $fila['puntosGanados'] . "</strong> puntos<br>Redujiste <strong>" . $fila['impactoCO2'] . " kg</strong> de CO2</div>";
                } else {
                    echo "<div class='message error'><h3>Algo salió mal</h3></div>";
                }
            }
        ?>

        <a href="puntosReciclajeCercanos.php">← Volver</a>
    </div>
</body>
</html>
