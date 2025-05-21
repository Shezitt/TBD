<?php
  session_start();
  require_once("conexion.php");

  $idRecompensa = $_GET['idRecompensa'];

?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Canjear recompensa</title>
  <style>
    /* (el mismo estilo que antes) */
    body { font-family: Arial, sans-serif; background-color: #f4f8fa; margin: 0; padding: 20px; }
    .container { max-width: 900px; margin: auto; }
    .header { text-align: center; margin-bottom: 20px; }
    .header h1 { color: #0a2d3c; }
    .btn-verde { background-color: #98eb9a; color: #0a2d3c; font-weight: bold; padding: 10px 20px; border: none; border-radius: 6px; cursor: pointer; margin-bottom: 20px; }
    table { width: 100%; border-collapse: collapse; text-align: center; }
    thead { background-color: #032d3c; color: white; }
    tbody tr:nth-child(even) { background-color: #e0e0e0; }
    td, th { padding: 10px; border: 1px solid #ccc; }
    .btn-anterior { margin-top: 30px; display: inline-block; background-color: #0a2d3c; color: white; padding: 10px 25px; font-weight: bold; text-decoration: none; border-radius: 4px; }
    .canjear-btn { background-color: #0a2d3c; color: white; padding: 6px 12px; border: none; border-radius: 4px; cursor: pointer; }
    .texto-no { color: #555; }
  </style>
</head>
<body>
  <div class="container">
    <div class="header">
      <h1>Canje de recompensa</h1>
    </div>

    <div class="cuerpo">
      
        <p>
            <?php
            
                // Verificar si tiene nivel suficiente
                $stmt = $conn->prepare("CALL sp_verificarNivelUsuarioRecompensa(?, ?);");
                $stmt->bind_param("ii", $idRecompensa, $_SESSION['idUsuario']);
                $stmt->execute();
                $res = $stmt->get_result()->fetch_assoc();
                $stmt->close();

                $nivelSuficiente = $res['respuesta'] == 1;

                // Verificar si tiene puntos suficientes
                $stmt = $conn->prepare("CALL sp_verificarPuntosUsuarioRecompensa(?, ?);");
                $stmt->bind_param("ii", $idRecompensa, $_SESSION['idUsuario']);
                $stmt->execute();
                $res = $stmt->get_result()->fetch_assoc();
                $stmt->close();

                $puntosSuficientes = $res['respuesta'] == 1;

                if ($nivelSuficiente && $puntosSuficientes) {
                    // hacer el canje

                    $stmt = $conn->prepare("CALL sp_canjearRecompensa(?, ?);");
                    $stmt->bind_param("ii", $_SESSION['idUsuario'], $idRecompensa);
                    $stmt->execute();
                    $stmt->close();

                    echo "<b>Canje exitoso!</b>";

                }
                else {
                    echo "<b>ERROR</b> no cumples los requisitos para este canje";
                }

            ?>
        </p>
      
      <a class="btn-anterior" href="catalogo.php">Volver</a>
      
    </div>
  </div>

  
  

</body>
</html>
