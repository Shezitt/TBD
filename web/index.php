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
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f7fa;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            min-height: 100vh;
        }

        .container {
            width: 90%;
            max-width: 1100px;
            background-color: #ffffff;
            border-radius: 12px;
            box-shadow: 0 4px 14px rgba(0, 0, 0, 0.1);
            padding: 30px 40px;
            margin-top: 10px;
            position: relative;
            overflow: hidden;
        }

        /* Rayita decorativa arriba */
        .container::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            height: 6px;
            width: 100%;
            background: linear-gradient(to right, #4CAF50, #81C784);
        }

        .barra-superior {
            display: none;
        }

        .bienvenida {
            display: flex;
            align-items: center;
            margin-bottom: 30px;
        }

        .logo {
            height: 60px;
            margin-right: 20px;
        }

        .texto-bienvenida h1 {
            font-size: 28px;
            color: #333;
            margin: 0;
        }

        .nombre-usuario {
            font-weight: bold;
            color: #388e3c;
            font-size: 16px;
        }

        .contenido {
            display: flex;
            gap: 40px;
            flex-wrap: wrap;
        }

        .columna-izquierda,
        .columna-derecha {
            flex: 1 1 45%;
        }

        .columna-derecha {
            background-color: #012030;
            color: #ffffff;
            padding: 20px;
            border-radius: 10px;
            max-height: 640px;
            overflow-y: auto;
            margin-top: -98px;

        }


        .columna-derecha h2 {
            text-align: center;
            font-size: 24px;
            margin-bottom: 20px;
            border-bottom: 2px solid #ffffff33;
            padding-bottom: 10px;
        }

        .datos-usuario {
            margin-bottom: 30px;
        }

        .datos-usuario p {
            font-size: 15px;
            color: #333;
            margin-bottom: 8px;
        }

        .text-wrapper {
            font-weight: bold;
            color: #555;
        }

        .boton-link {
            display: block;
            background-color:rgba(144, 238, 144, 0.87);
            color: #012030;
            padding: 14px 20px;
            margin: 6px 0;
            border-radius: 6px;
            text-decoration: none;
            font-weight: bold;
            text-align: center;
            font-size: 16px;
            transition: background-color 0.3s ease, transform 0.2s ease;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
        }

        .boton-link:hover {
            background-color: #7CFC00;
            transform: translateY(-2px);
        }


        ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }




        .columna-derecha h2 {
            text-align: center;
            font-size: 2em;
            margin-bottom: 20px;
        }


        .boton-cerrar {
            background-color: #2c3e50;
            color: #ffffff;
            margin-top: 20px;
        }

        .boton-cerrar:hover {
            background-color: #1a252f;
        }

        .titulo-botones {
            font-size: 20px;
            margin-top: 10px;
            color: #333;
            border-left: 4px solid #4CAF50;
            padding-left: 10px;
        }

        .promocion {
            display: flex;
            align-items: center;
            background-color: rgba(255, 255, 255, 0.05);
            border: 1px solid #ffffff22;
            border-radius: 8px;
            padding: 10px;
            margin-bottom: 15px;
        }

        .promocion img {
            width: 80px;
            height: auto;
            border-radius: 6px;
            margin-right: 15px;
        }

        .promocion-detalle h3 {
            margin: 0;
            font-size: 18px;
            color: #ffffff;
        }

        .promocion-detalle b {
            color: #cfd8dc;
        }
    </style>



</head>

<body>

    <div class="container">

        <div class="bienvenida">
            <img class="logo" src="logo.png" alt="Logo del sitio">
            <div class="texto-bienvenida">
                <h1>BIENVENIDO</h1>
                <div class="nombre-usuario"><?php echo $datosUsuario['nombre']; ?></div>
            </div>
        </div>

        <div class="contenido">

            <div class="columna-izquierda">

                <div class="datos-usuario">

                    <div class="label">
                        <div class="flexcontainer">
                            <p class="text">
                                <span class="text-wrapper">Nombre de usuario:</span>
                                <span class="span"> <?php echo $datosUsuario['username']; ?></span>
                            </p>
                            <p class="text">
                                <span class="text-wrapper">Correo:</span>
                                <span class="span"> <?php echo $datosUsuario['correo']; ?></span>
                            </p>
                            <p class="text">
                                <span class="text-wrapper">Puntos:</span>
                                <span class="span"> <?php echo $datosUsuario['puntos']; ?></span>
                            </p>
                            <p class="text">
                                <span class="text-wrapper">Puntos históricos:</span>
                                <span class="span"> <?php echo $datosUsuario['puntosTotal']; ?></span>
                            </p>
                            <p class="text">
                                <span class="text-wrapper">Nivel:</span>
                                <span class="span"> <?php echo $_SESSION['nivel']; ?> </span>
                            </p>
                        </div>
                    </div>


                    <p>
                        <a class="boton-link boton-cerrar" href="logout.php">Cerrar sesión</a>

                    </p>



                    <h2 class="titulo-botones">MENU</h2>
                    <ul>

                        <?php if (in_array("dashboard", $_SESSION['permisos'])): ?>
                            <li><a class="boton-link" href="panelAdministrativo.php">Ingresar a panel administrativo</a></li>
                        <?php endif; ?>

                        <li>
                            <a class="boton-link" href="verPuntosReciclaje.php">Ver puntos de reciclaje</a>
                        </li>
                        <li>
                            <a class="boton-link" href="catalogo.php">Ver recompensas</a>
                        </li>
                        <li>
                            <a class="boton-link" href="reporteImpacto.php">Ver impacto ambiental</a>
                        </li>
                        <li>
                            <a class="boton-link" href="ranking.php">Ver ránking</a>
                        </li>
                        <li>
                            <a class="boton-link" href="bitacora.php">Ver bitácora personal</a>
                        </li>

                    </ul>
                </div>

            </div>

            <div class="columna-derecha">
                <h2>Promociones</h2>
                <?php

                $stmt = $conn->prepare('SELECT * FROM Promocion WHERE activo=1;');
                $stmt->execute();
                $resultado = $stmt->get_result();
                $stmt->close();

                if ($resultado->num_rows == 0) {
                    echo "<p>No hay promociones actualmente.</p>";
                } else {
                    $contador = 1;

                    while ($fila = $resultado->fetch_assoc()) {
                        $nombreImagen = "images/promos/promo-" . $contador . ".jpg"; 
                
                        echo '<div class="promocion" style="display: flex; align-items: center; gap: 15px; margin-bottom: 20px;">';
                        echo '<img src="' . $nombreImagen . '" alt="Imagen promoción" style="width: 100px; height: auto; border-radius: 8px;">';
                        echo '<div class="promocion-detalle">';
                        echo '<h3>' . $fila['nombre'] . '</h3>';
                        echo '<b>Multiplicador:</b> ' . $fila['multiplicador'] . '<br>';
                        echo '<b>Fecha inicio:</b> ' . $fila['fechaInicio'] . '<br>';
                        echo '<b>Fecha fin:</b> ' . $fila['fechaFin'] . '<br>';
                        echo 'Nivel requerido: ' . $fila['nivelRequerido'];
                        echo '</div></div>';

                        $contador++;
                    }

                }

                ?>
            </div>
        </div>
    </div>
</body>

</html>