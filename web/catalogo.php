<?php
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
      <?php
        if (isset($_POST['catalogo'])) {
          echo "<table>";
          echo "<thead>";
          echo "<tr>";
          
          echo "</tr>";
          echo "</thead>";
        }

      ?>
      <table>
        <thead>
          <tr>
            <th>Nombre</th>
            <th>Puntos necesarios</th>
            <th>Nivel Requerido</th>
            <th>Canjear</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>Recompensa 1</td>
            <td>100</td>
            <td>Nivel 1</td>
            <td><button class="canjear-btn">Canjear</button></td>
          </tr>
          <tr>
            <td>Recompensa 2</td>
            <td>500</td>
            <td>Nivel 3</td>
            <td class="texto-no">No tienes puntos suficientes</td>
          </tr>
          <tr>
            <td>Recompensa 3</td>
            <td>500</td>
            <td>Nivel 3</td>
            <td class="texto-no">No tienes puntos suficientes</td>
          </tr>
        </tbody>
      </table>
    </div>

    <a class="btn-anterior" href="javascript:history.back()">Anterior</a>
  </div>

  <script>
    // Tomamos todos los botones "Canjear"
    const botones = document.querySelectorAll('.canjear-btn');

    botones.forEach(btn => {
      btn.addEventListener('click', () => {
        alert('¡Canjeo exitoso!'); // Mensaje simple
        // Aquí puedes agregar lógica adicional, enviar datos al servidor, etc.
      });
    });
  </script>
</body>
</html>
