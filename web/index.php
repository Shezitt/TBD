<?php
    require_once("conexion.php");

    session_start();

    if (!isset($_SESSION['username'])) {
        header("Location: iniciarSesion.php");
        exit();
    }
    $idUsuario = $_SESSION['idUsuario'];
    $stmt = $conn->prepare("CALL sp_getDatosUsuario(?);");
    $stmt->bind_param("i", $idUsuario);
    $stmt->execute();
    $datosUsuario = $stmt->get_result();
    $stmt->close();
    $datosUsuario = $datosUsuario->fetch_assoc();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienvenido</title>
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
            <h1>BIENVENIDO</h1>
        </div>

        <div class="cuerpo">
            <h2>Datos</h2>
            <ul>
                <li>
                    Nombre: <?php echo $datosUsuario['nombre']; ?>
                </li>
                <li>
                    Nombre de usuario: <?php echo $datosUsuario['username']; ?>
                </li>
                <li>
                    Correo: <?php echo $datosUsuario['correo']; ?>
                </li>
                <li>
                    Puntos: <?php echo $datosUsuario['puntos']; ?>
                </li>
                <li>
                    Puntos históricos: <?php echo $datosUsuario['puntosTotal']; ?>
                </li>
            </ul>

            <p>
                <a href="logout.php">Cerrar sesión</a>
            </p>

            <h2>Promociones</h2>
            <?php
                
                $stmt = $conn->prepare('CALL sp_getPromocionesUsuario(?);');
                $stmt->bind_param("i", $idUsuario);
                $stmt->execute();
                $resultado = $stmt->get_result();
                $stmt->close();
                
                if($resultado->num_rows == 0) {
                    echo "<p>No hay promociones actualmente.</p>";
                } else {

                    while ($fila = $resultado->fetch_assoc()) {
                        echo "<h3>" . $fila['nombre'] . "</h3>";
                        echo "<b>Multiplicador:</b> " . $fila['multiplicador'] . "<br>";
                        echo "<b>Fecha inicio:</b> " . $fila['fechaInicio'] . "<br>";
                        echo "<b>Fecha fin:</b> " . $fila['fechaFin'] . "<br>";
                        echo "Nivel requerido: " . $fila['nivelRequerido']; 
                    }

                }

            ?>

            <h2>Botones</h2>
            <ul>
                <?php
                    if ($_SESSION['rol'] == 1) {
                        echo '<li>
                            <a href="panelAdministrativo.php">Ingresar a panel administrativo</a>
                        </li>';
                    }
                ?>
                
                <li>
                    <a href="verPuntosReciclaje.php">Ver puntos de reciclaje</a>
                </li>
                <li>
                    <a href="catalogo.php">Ver recompensas</a>
                </li>
                <li>
                    <a href="reporteImpacto.php">Ver impacto ambiental</a>
                </li>
                <li>
                    <a href="ranking.php">Ver ránking</a>
                </li>
            </ul>
        </div>

        
        
    </div>  
</body>
</html>