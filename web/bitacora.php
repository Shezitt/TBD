<?php
session_start();
require_once("conexion.php");
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />

  <title>Bitácora personal</title>
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
      box-shadow: 0 0 15px rgba(0, 0, 0, 0.08);
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
      box-shadow: 0 0 5px rgba(0, 0, 0, 0.05);
    }

    thead th {
      background-color: #90EE90;
      color: #012030;
      padding: 14px 20px;
      margin: 6px 0;
      text-decoration: none;
      font-weight: bold;
      text-align: center;
      font-size: 16px;
      transition: background-color 0.3s ease, transform 0.2s ease;

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
      background-color: #2c3e50;
      color: white;
      padding: 12px 28px;
      text-decoration: none;
      font-weight: bold;
      border: none;
      cursor: pointer;
      font-size: 16px;
      border-radius: 8px;
      margin-top: 20px;
      transition: background 0.3s;
    }

    .btn:hover {
      background-color: #1a252f;
    }
  </style>
</head>

<body>
  <div class="container">
    <div class="header">
      <h1>BITÁCORA PERSONAL</h1>
    </div>

    <div class="cuerpo">
      <h2>Historial de reciclaje</h2>
      <table>
        <thead>
          <tr>
            <th>ID</th>
            <th>Fecha</th>
            <th>Punto</th>
            <th>Material</th>
            <th>Cantidad (kg)</th>
            <th>Puntos ganados</th>
            <th>Impacto CO2 (kg)</th>
            <th>Impacto Agua (L)</th>
            <th>Impacto Energía (kWh)</th>
          </tr>
        </thead>
        <tbody>
          <?php
          $stmt = $conn->prepare("CALL listar_reciclaje_usuario(?);");
          $stmt->bind_param("i", $_SESSION["idUsuario"]);
          $stmt->execute();
          $resultado = $stmt->get_result();
          $stmt->close();

          while ($fila = $resultado->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $fila['idRegistro'] . "</td>";
            echo "<td>" . $fila['fecha'] . "</td>";
            echo "<td>" . $fila['nombrePunto'] . "</td>";
            echo "<td>" . $fila['material'] . "</td>";
            echo "<td>" . $fila['cantidad'] . "</td>";
            echo "<td>" . $fila['puntosGanados'] . "</td>";
            echo "<td>" . $fila['impactoCO2'] . "</td>";
            echo "<td>" . $fila['impactoAgua'] . "</td>";
            echo "<td>" . $fila['impactoEnergia'] . "</td>";
            echo "</tr>";
          }
          ?>
        </tbody>
      </table>

      <h2>Historial de canjes</h2>
      <table>
        <thead>
          <tr>
            <th>ID</th>
            <th>Fecha</th>
            <th>Catálogo</th>
            <th>Recompensa</th>
            <th>Completado</th>
          </tr>
        </thead>
        <tbody>
          <?php
          $stmt = $conn->prepare("CALL listar_canje_usuario(?);");
          $stmt->bind_param("i", $_SESSION["idUsuario"]);
          $stmt->execute();
          $resultado = $stmt->get_result();

          while ($fila = $resultado->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $fila['idCanje'] . "</td>";
            echo "<td>" . $fila['fecha'] . "</td>";
            echo "<td>" . $fila['nombreCatalogo'] . "</td>";
            echo "<td>" . $fila['nombre'] . "</td>";
            echo "<td>" . ($fila['completado'] == '1' ? 'Si' : 'No') . "</td>";
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