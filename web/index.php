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

         * {
            font-family: 'Trebuchet MS', sans-serif;
        }

        .barra-superior {
            width: 100%;
            height: 30px;
            border-bottom: 2px solid #B2B2B2;
            display: flex;
            align-items: center;
            justify-content: flex-end;
            padding: 0 20px;
            bakground-color: white;
            box-sizing: border-box;
        }

        .container {
            maax-width: 1200px;
            margin: auto auto;
            padding: 20px;
            background-color: white;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }

        .header h1 {
            color: #012030;
            font-size: 2.8em;
            margin-bottom: 10px;
            text-align: left;
        }

        .contenido {
            display: flex;
            gap: 40px;
        }

        .columna-izquierda {
            width: 50%;
            
        }

        .datos-usuario ul,
        .botones ul {
            list-style-type: none;
            padding-left: 0;
            margin: auto 100px;

        }

        .bienvenida {
            display: flex;
            align-items: center;
            gap: 10px;
            margin: 10px 60px;
        }

        .logo {
            height: 50px;
        }

        .texto-bienvenida h1 {
            color: #012030;
            font-size: 2em;
            margin: 0;
        }

        .nombre-usuario {
            color: #012030;
            font-size: 1em;
            font-weight: bold;
            margin-top: 4px;
        }

        .flexcontainer{
            margin: auto 150px;
        }

         .text-wrapper {
          font-weight: bold;
        }


        .titulo-botones {
            text-align: left;
            margin-right: 10px;
            margin-left: 120px;
        }

        .datos-usuario li {
            color: #012030;
            margin-bottom: 6px;
        }

        .botones li {
            margin-bottom: 10px;
        }

        .botones a {
            text-decoration: none;
            color: #1e90ff;
        }

        .botones a:hover {
            text-decoration: underline;
        }

        .columna-derecha {
            width: 60%;
        }

        .boton-link {
            display: inline-block;
            background-color: #9AEBA3;
            color: #012030;
            text-align: center;
            padding: 10px 15px;
            margin: 1px 0;
            border: none;
            border-radius: 8px;
            text-decoration: none;
            font-weight: bold;
            font-family: "Trebuchet MS", sans-serif;
            transition: background-color 0.2s ease;
            width: 500px; height: 25px;
        }

        .boton-link:hover {
            background-color: #86d890;
        }

        .boton-link:active {
            background-color: #B2B2B2;
        }


        .boton-cerrar {
            background-color: #012030; /* azul medio */
            color: white;
            width: 100px; height: 25px;
            margin: auto 100px;
        }

        .boton-cerrar:hover {
            background-color: #012030; /* azul más oscuro en hover */
        }

        .boton-cerrar:active {
            background-color: #B2B2B2; /* igual que los demás, plomo al hacer clic */
        }

        .promo {
            background-color: #f0f8ff;
            padding: 10px;
            border-left: 4px solid #012030;
            margin-bottom: 15px;
            border-radius: 6px;
        }

        .columna-derecha {
            background-color: #012030;
            color: white;
            padding: 20px;
            height: 100vh;
            overflow-y: auto;
            box-sizing: border-box;
            max-height: 650px; 
            margin-top: -80px; 
            border-radius: 8px;
        }


        .columna-derecha h2 {
        text-align: center;
        font-size: 2em;
        margin-bottom: 20px;
        }

        .promocion {
        display: flex;
        align-items: center;
        background-color: rgba(255, 255, 255, 0.05);
        border: 1px solid #ffffff22;
        border-radius: 8px;
        padding: 10px;
        margin-bottom: 10px;
        }

        .promocion img {
        width: 60px;
        height: 60px;
        object-fit: cover;
        margin-right: 15px;
        border-radius: 6px;
        }

        .promocion-detalle {
        flex: 1;
        }
                
    </style>
    
</head>
<body>

    <div class="barra-superior">
        <!-- Aquí puedes colocar un botón más adelante -->
    </div>

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
                                <span class="span"> Plata</span> <!-- Puedes reemplazar con un campo real -->
                            </p>
                        </div>
                    </div>


                    <p>
                        <a class="boton-link boton-cerrar" href="logout.php">Cerrar sesión</a>

                    </p>

                    

                    <h2 class="titulo-botones">Menu ;)</h2>
                    <ul>

                        <?php 
                            if ($_SESSION['rol'] == 2) {
                                echo '<li><a class="boton-link" href="panelAdministrativo.php">Ingresar a panel administrativo</a></li>';
                            }
                        ?>

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

                    </ul>
                </div>

            </div>

            <div class="columna-derecha">
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
                            $contador = 1;

                            while ($fila = $resultado->fetch_assoc()) {
                                $nombreImagen = "promo-" . $contador . ".jpg"; // promo-1.jpg, promo-2.jpg, etc.

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