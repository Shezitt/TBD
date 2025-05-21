<?php
  session_start();
  require_once("conexion.php");

  $stmt = $conn->prepare("CALL sp_getCatalogos()");
  $stmt->execute();
  $catalogos = $stmt->get_result();
  $stmt->close();

?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Catálogo de Recompensas</title>
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
      <h1>CATÁLOGO DE RECOMPENSAS</h1>
    </div>

    <form method="POST">
      <select name="catalogo">
        <?php
          while ($fila = $catalogos->fetch_assoc()) {
            $idCatalogo = $fila['idCatalogo'];
            echo "<option value='$idCatalogo'>" . $fila['nombreCatalogo'] . "</option>";
          }
        ?>
      </select>
      <input type="submit" value="Seleccionar catálogo">
    </form>

    <div class="cuerpo">
      <div class="tabla-contenedor">
          <h2>
            Catálogo:
            <?php
              $catalogoSel = (isset($_POST['catalogo']) ? $_POST['catalogo'] : 1);
              $stmt = $conn->prepare("SELECT nombreCatalogo as nombre FROM Catalogo WHERE idCatalogo='$catalogoSel';");
              $stmt->execute();
              $resultado = $stmt->get_result()->fetch_assoc();
              echo $resultado['nombre'];
            ?>
          </h2>
          <?php
            $catalogoSel = (isset($_POST['catalogo']) ? $_POST['catalogo'] : 1);
            echo "<table>";
            echo "<thead>";
            echo "<tr>";
            echo "<th>Nombre</th>";
            echo "<th>Puntos necesarios</th>";
            echo "<th>Nivel requerido</th>";
            echo "<th>Canjear</th>";
            echo "</tr>";
            echo "</thead>";
            echo "<tbody>";

            $stmt = $conn->prepare("CALL sp_getRecompensasCatalogo(?);");
            $stmt->bind_param("i", $catalogoSel);
            $stmt->execute();
            $resultado = $stmt->get_result();
            $stmt->close();

            while ($fila = $resultado->fetch_assoc()) {
              echo "<tr>";
              echo "<td>" . $fila['nombre'] . "</td>";
              echo "<td>" . $fila['puntosNecesarios'] . "</td>";
              echo "<td>" . $fila['nivelRequerido'] . "</td>";
              
              $idRecompensa = $fila['idRecompensa'];

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

              echo "<td>";
              if ($nivelSuficiente && $puntosSuficientes) {
                echo "<a href='canjear.php?idRecompensa=$idRecompensa'>Canjear</a>";
              } else {
                if ($nivelSuficiente) {
                  echo "No tienes puntos suficientes";
                } else if ($puntosSuficientes){
                  echo "No tienes nivel suficiente";
                } else {
                  echo "No tienes puntos suficientes";
                  echo "<br>No tienes nivel suficiente";
                }
              }
              echo "</td>";

              echo "</tr>";
            }

            echo "</tbody>";
            echo "</table>";

        ?>
      </div>
      
      <a class="btn-anterior" href="index.php">Volver</a>
      
    </div>
  </div>

  
  

</body>
</html>
