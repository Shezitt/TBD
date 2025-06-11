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
      font-family: 'Segoe UI', sans-serif;
      background-color: #f0f4f8;
      margin: 0;
      padding: 0;
    }

    .container {
      max-width: 900px;
      margin: 60px auto;
      padding: 30px;
      background: #ffffff;
      border-radius: 12px;
      box-shadow: 0 0 15px rgba(0,0,0,0.08);
    }

    .header {
      text-align: center;
      margin-bottom: 30px;
    }

    .header h1 {
      font-size: 32px;
      color: #2c3e50;
      margin: 0;
    }

    .cuerpo h2 {
      font-size: 24px;
      color: #34495e;
      margin-top: 30px;
      margin-bottom: 15px;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 30px;
      box-shadow: 0 0 5px rgba(0,0,0,0.05);
    }

    thead th {
      background-color: #27ae60;
      color: white;
      padding: 12px;
      text-align: left;
      font-size: 16px;
    }

    tbody td {
      background-color: #ecf0f1;
      padding: 12px;
      border-bottom: 1px solid #d0d7de;
      font-size: 15px;
    }

    tbody tr:last-child td {
      border-bottom: none;
    }

    .button-container {
      display: flex;
      justify-content: flex-start;
    }

    .btn {
      background-color: #27ae60;
      color: white;
      padding: 12px 28px;
      text-decoration: none;
      font-weight: bold;
      border: none;
      cursor: pointer;
      font-size: 16px;
      border-radius: 8px;
      transition: background 0.3s;
    }

    .btn:hover {
      background-color: #219150;
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
            <th>Total CO2 reducido (kg)</th>
            <th>Total ahorro de agua (L)</th>
            <th>Total ahorro de energía (kWh)</th>
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
              echo "<td>" . $fila['total_agua_reducido'] . "</td>";
              echo "<td>" . $fila['total_energia_reducido'] . "</td>";
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
            <th>Total CO2 reducido (kg)</th>
            <th>Total ahorro de agua (L)</th>
            <th>Total ahorro de energía (kWh)</th>
            <th>Tu impacto respecto al total</th>
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
              echo "<td>" . $fila['total_agua_reducido'] . "</td>";
              echo "<td>" . $fila['total_energia_reducido'] . "</td>";
              echo "<td>" . $fila['porcentaje_usuario'] . " %</td>";
              echo "</tr>";
            }
          ?>
        </tbody>
      </table>
    </div>

    <div class="button-container">
      <button class="btn" onclick="history.back()">← Volver</button>
    </div>
  </div>
</body>
</html>
