<?php
    require_once("conexion.php");

    session_start();

    if (!isset($_SESSION['username'])) {
        header("Location: iniciarSesion.php");
        exit();
    }
    $idUsuario = $_SESSION['idUsuario'];
    $conn = Conexion::getConexion();
    $stmt = $conn->prepare("CALL sp_getDatosUsuario(?);");
    $stmt->bind_param("i", $idUsuario);
    $stmt->execute();
    $resultado = $stmt->get_result();
    $resultado = $resultado->fetch_assoc();

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
                    Nombre: <?php echo $resultado['nombre']; ?>
                </li>
                <li>
                    Nombre de usuario: <?php echo $resultado['username']; ?>
                </li>
                <li>
                    Correo: <?php echo $resultado['correo']; ?>
                </li>
                <li>
                    Puntos: <?php echo $resultado['puntos']; ?>
                </li>
                <li>
                    Puntos históricos: <?php echo $resultado['puntosTotal']; ?>
                </li>
            </ul>

            <p>
                <a href="logout.php">Cerrar sesión</a>
            </p>

            <h2>Promociones</h2>
            <?php
                
                $conn = Conexion::getConexion();
                $stmt = $conn->prepare('CALL sp_getPromocionesUsuario(?);');
                $stmt->bind_param("i", $idUsuario);
                $stmt->execute();
                $resultado = $stmt->get_result();
                
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
                <li>
                    <a href="panelAdministrativo.php">Ingresar a panel administrativo</a>
                </li>
                <li>
                    <a href="">Ver puntos de reciclaje</a>
                </li>
                <li>
                    <a href="">Ver recompensas</a>
                </li>
                <li>
                    <a href="">Ver impacto ambiental</a>
                </li>
                <li>
                    <a href="">Ver ránking</a>
                </li>
            </ul>
        </div>

        
        
    </div>  
</body>
</html>