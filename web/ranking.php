<?php
session_start();
require_once("conexion.php");
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Ránking de usuarios</title>
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
      <h1>RÁNKING DE USUARIOS</h1>
    </div>

    <div class="cuerpo">
      <table>
        <thead>
          <tr>
            <th>Nº</th>
            <th>Nombre de usuario</th>
            <th>Nombre</th>
            <th>Nivel</th>
            <th>Total de puntos</th>
          </tr>
        </thead>
        <tbody>
          <?php
          $stmt = $conn->prepare("CALL getRankingPuntos();");
          $stmt->execute();
          $resultado = $stmt->get_result();

          $cnt = 1;
          while ($fila = $resultado->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $cnt++ . "</td>";
            echo "<td>" . $fila['username'] . "</td>";
            echo "<td>" . $fila['nombre'] . "</td>";
            echo "<td>" . $fila['nivel'] . "</td>";
            echo "<td>" . $fila['puntosTotal'] . "</td>";
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
