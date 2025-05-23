<?php
  session_start();
  require_once("conexion.php");
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Reporte de impacto ambiental</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: white;
      margin: 0;
      padding: 0;
    }

    .container {
      max-width: 900px;
      margin: 40px auto;
      padding: 20px;
    }

    .header {
      text-align: center;
      margin-bottom: 20px;
    }

    .header h1 {
      font-size: 24px;
      color: #003049;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      border: 2px solid #007BFF;
    }

    thead th {
      background-color: #002b49;
      color: white;
      padding: 10px;
      border: 1px solid #007BFF;
      text-align: left;
    }

    tbody td {
      background-color: #d9d9d9;
      padding: 10px;
      border: 1px solid #007BFF;
    }

    .button-container {
      margin-top: 30px;
      display: flex;
      justify-content: flex-start;
    }

    .btn {
      background-color: #001f2d;
      color: white;
      padding: 10px 30px;
      text-decoration: none;
      font-weight: bold;
      border: none;
      cursor: pointer;
      font-size: 16px;
    }

    .btn:hover {
      background-color: #003049;
    }
  </style>
</head>
<body>
  <div class="container">
    <div class="header">
      <h1>REPORTE DE IMPACTO AMBIENTAL</h1>
    </div>

    <div class="cuerpo">
      <h2>REPORTE GENERAL</h2>
      <table>
        <thead>
          <tr>
            <th>Nº</th>
            <th>Material</th>
            <th>Total reciclado (kg)</th>
            <th>Total CO2 reducido(kg)</th>
          </tr>
        </thead>
        <tbody>
        
          <?php
          
            $stmt = $conn->prepare("CALL GenerarReporteImpactoAmbientalHistorico();");
            $stmt->execute();
            $resultado = $stmt->get_result();
            $stmt->close();

            $cnt = 1;

            while ($fila = $resultado->fetch_assoc()) {
              echo "<tr>";
              echo "<td>" . $cnt++ . "</td>";
              echo "<td>" . $fila['tipo_material'] . "</td>";
              echo "<td>" . $fila['total_reciclado_kg'] . "</td>";
              echo "<td>" . $fila['total_co2_reducido'] . "</td>";
              echo "</tr>";
            }

          ?>

        </tbody>
      </table>

      <h2>TU IMPACTO</h2>
      <table>
        <thead>
          <tr>
            <th>Nº</th>
            <th>Material</th>
            <th>Total reciclado (kg)</th>
            <th>Total CO2 reducido(kg)</th>
          </tr>
        </thead>
        <tbody>
        
          <?php
          
            $stmt = $conn->prepare("CALL sp_getImpactoUsuario(?);");
            $stmt->bind_param("i", $_SESSION['idUsuario']);
            $stmt->execute();
            $resultado = $stmt->get_result();
            $stmt->close();

            $cnt = 1;

            while ($fila = $resultado->fetch_assoc()) {
              echo "<tr>";
              echo "<td>" . $cnt++ . "</td>";
              echo "<td>" . $fila['tipo_material'] . "</td>";
              echo "<td>" . $fila['total_reciclado_kg'] . "</td>";
              echo "<td>" . $fila['total_co2_reducido'] . "</td>";
              echo "</tr>";
            }

          ?>

        </tbody>
      </table>
    </div>

    <div class="button-container">
      <button class="btn" onclick="history.back()">Anterior</button>
    </div>
  </div>
</body>
</html>
