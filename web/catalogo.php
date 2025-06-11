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
    /* Igual que la página canjes pendientes */
    body {
      font-family: Arial, sans-serif;
      margin: 0;
      padding: 20px;
      background-color: #f4f8fa;
      color: #0a2d3c;
    }
    .container {
      max-width: 800px;
      margin: auto;
    }
    .header {
      text-align: center;
      margin-bottom: 20px;
    }
    .header h1 {
      color: #0a2d3c;
      margin: 0;
    }
    form {
      margin-bottom: 20px;
      text-align: center;
    }
    select {
      padding: 8px 12px;
      border-radius: 6px;
      border: 1px solid #ccc;
      font-size: 1rem;
      min-width: 200px;
    }
    input[type="submit"] {
      background-color: #98eb9a;
      color: #0a2d3c;
      font-weight: bold;
      padding: 8px 20px;
      border: none;
      border-radius: 6px;
      cursor: pointer;
      margin-left: 10px;
      font-size: 1rem;
    }
    .cuerpo h2 {
      margin-top: 0;
      text-align: center;
    }
    table {
      width: 100%;
      border-collapse: collapse;
      margin: 20px 0;
      text-align: center;
    }
    thead {
      background-color: #032d3c;
      color: white;
    }
    tbody tr:nth-child(even) {
      background-color: #e0e0e0;
    }
    th, td {
      padding: 10px;
      border: 1px solid #ccc;
    }
    a {
      color: #0a2d3c;
      text-decoration: none;
      font-weight: bold;
    }
    a:hover {
      text-decoration: underline;
    }
    .btn-anterior {
      display: inline-block;
      background-color: #0a2d3c;
      color: white;
      padding: 10px 25px;
      font-weight: bold;
      text-decoration: none;
      border-radius: 4px;
      margin-top: 30px;
      text-align: center;
    }
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
            $selected = (isset($_POST['catalogo']) && $_POST['catalogo'] == $idCatalogo) ? "selected" : "";
            echo "<option value='$idCatalogo' $selected>" . $fila['nombreCatalogo'] . "</option>";
          }
        ?>
      </select>
      <input type="submit" value="Seleccionar catálogo" />
    </form>

    <div class="cuerpo">
      <h2>
        Catálogo: 
        <?php
          $catalogoSel = isset($_POST['catalogo']) ? $_POST['catalogo'] : 1;
          $stmt = $conn->prepare("SELECT nombreCatalogo as nombre FROM Catalogo WHERE idCatalogo=?;");
          $stmt->bind_param("i", $catalogoSel);
          $stmt->execute();
          $resultado = $stmt->get_result()->fetch_assoc();
          $stmt->close();
          echo htmlspecialchars($resultado['nombre']);
        ?>
      </h2>

      <table>
        <thead>
          <tr>
            <th>Nombre</th>
            <th>Puntos necesarios</th>
            <th>Nivel requerido</th>
            <th>Canjear</th>
          </tr>
        </thead>
        <tbody>
          <?php
            $stmt = $conn->prepare("CALL sp_getRecompensasCatalogo(?);");
            $stmt->bind_param("i", $catalogoSel);
            $stmt->execute();
            $resultado = $stmt->get_result();
            $stmt->close();

            while ($fila = $resultado->fetch_assoc()) {
              echo "<tr>";
              echo "<td>" . htmlspecialchars($fila['nombre']) . "</td>";
              echo "<td>" . htmlspecialchars($fila['puntosNecesarios']) . "</td>";
              echo "<td>" . htmlspecialchars($fila['nivelRequerido']) . "</td>";

              $idRecompensa = $fila['idRecompensa'];

              $stmt = $conn->prepare("CALL sp_verificarNivelUsuarioRecompensa(?, ?);");
              $stmt->bind_param("ii", $idRecompensa, $_SESSION['idUsuario']);
              $stmt->execute();
              $res = $stmt->get_result()->fetch_assoc();
              $stmt->close();
              $nivelSuficiente = $res['respuesta'] == 1;

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
                } else if ($puntosSuficientes) {
                  echo "No tienes nivel suficiente";
                } else {
                  echo "No tienes puntos suficientes<br>No tienes nivel suficiente";
                }
              }
              echo "</td>";

              echo "</tr>";
            }
          ?>
        </tbody>
      </table>

      <a href="index.php" class="btn-anterior">Volver</a>
    </div>
  </div>
</body>
</html>
