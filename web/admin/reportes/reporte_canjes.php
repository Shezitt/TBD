<?php
session_start();

if (!in_array("dashboard", $_SESSION['permisos']) or !in_array("dashboard_reportes", $_SESSION['permisos'])) {
    header("Location: index.php");
    exit();
}

require_once("../../conexion.php");

$reporte_resultado = false;
$error = "";

if (isset($_POST['generar_reporte'])) {
    $fecha_inicio = $_POST['fecha_inicio'];
    $fecha_fin = $_POST['fecha_fin'];

    if ($fecha_inicio && $fecha_fin) {
        $stmt = $conn->prepare("CALL sp_reporteCanjes(?, ?)");
        if ($stmt) {
            $stmt->bind_param("ss", $fecha_inicio, $fecha_fin);
            $stmt->execute();
            $reporte_resultado = $stmt->get_result();
        } else {
            $error = "Error al preparar la consulta: " . $conn->error;
        }
    } else {
        $error = "Por favor, selecciona ambas fechas.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Reporte de Canjes</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f2f5;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            min-height: 100vh;
        }

        .container {
            width: 90%;
            max-width: 1000px;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            padding: 25px 35px;
            margin-top: 30px;
            margin-bottom: 30px;
        }

        .header {
            display: flex;
            align-items: center;
            padding-bottom: 20px;
            border-bottom: 1px solid #eee;
            margin-bottom: 25px;
        }

        .header .icon {
            font-size: 30px;
            color: #4CAF50;
            margin-right: 15px;
        }

        .header h1 {
            font-size: 28px;
            color: #333;
            margin: 0;
        }

        form {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            margin-bottom: 30px;
            align-items: flex-end;
        }

        form label {
            font-size: 14px;
            color: #555;
        }

        form input[type="date"],
        form input[type="submit"] {
            padding: 10px 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }

        form input[type="submit"] {
            background-color: #007bff;
            color: white;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        form input[type="submit"]:hover {
            background-color: #0056b3;
        }

        .table-container {
            border: 2px solid #007bff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        thead th {
            background-color: #007bff;
            color: white;
            padding: 12px 15px;
            text-align: left;
        }

        tbody td {
            padding: 10px 15px;
            border-bottom: 1px solid #ddd;
        }

        tbody tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        .back-button-container {
            margin-top: 30px;
        }

        .back-button {
            display: inline-block;
            background-color: #34495e;
            color: white;
            padding: 12px 25px;
            border-radius: 5px;
            text-decoration: none;
            font-weight: bold;
            transition: background-color 0.3s ease;
        }

        .back-button:hover {
            background-color: #2c3e50;
        }

        .error {
            color: red;
            margin-bottom: 15px;
        }
        canvas {
  background: #fff;
  box-shadow: 0 4px 12px rgba(0,0,0,0.1);
  border-radius: 12px;
  padding: 10px;
}



    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <i class="fas fa-gift icon"></i>
            <h1>REPORTE DE CANJES</h1>
        </div>

        <form method="POST">
            <div>
                <label for="fecha_inicio">Desde:</label>
                <input type="date" name="fecha_inicio" value="2025-01-01" required>
            </div>

            <div>
                <label for="fecha_fin">Hasta:</label>
                <input type="date" name="fecha_fin" value="<?php echo date('Y-m-d'); ?>" required>
            </div>

            <input type="submit" name="generar_reporte" value="Generar Reporte">
        </form>

        <?php if ($error) echo "<div class='error'>$error</div>"; ?>

        <?php if ($reporte_resultado && $reporte_resultado->num_rows > 0): ?>
            <div class="table-container">
                <table id="tablaCanjes">
                    <thead>
                        <tr>
                            <th>Nº</th>
                            <th>Recompensa</th>
                            <th>Total Canjes</th>
                            <th>Canjes Completados</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $contador = 1; ?>
                        <?php 
                            $total_canjes = 0; 
                            $total_completados = 0; 
                        ?>
                        <?php while ($fila = $reporte_resultado->fetch_assoc()): ?>
                            <?php
                                $total_canjes += $fila['total_canjes'];
                                $total_completados += $fila['canjes_completados'];
                            ?>
                            <tr>
                                <td><?php echo $contador++; ?></td>
                                <td><?php echo htmlspecialchars($fila['recompensa']); ?></td>
                                <td><?php echo $fila['total_canjes']; ?></td>
                                <td><?php echo $fila['canjes_completados']; ?></td>
                            </tr>
                        <?php endwhile; ?>
                        <tr style="font-weight: bold; background-color: #d9edf7;">
                            <td colspan="2">Totales</td>
                            <td><?php echo $total_canjes; ?></td>
                            <td><?php echo $total_completados; ?></td>
                        </tr>
                    </tbody>
                </table>


            </div>


<!-- 📊 Contenedor de gráficos -->
<div style="display: flex; flex-wrap: wrap; gap: 20px; justify-content: center; margin-top: 30px;">
  <div style="flex: 1 1 45%; min-height: 300px;">
    <canvas id="graficoBarras"></canvas>
  </div>
  <div style="flex: 1 1 45%; min-height: 300px;">
    <canvas id="graficoLinea"></canvas>
  </div>
  <div style="flex: 1 1 45%; min-height: 300px;">
    <canvas id="graficoPastel"></canvas>
  </div>
</div>




<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
  console.log(document.querySelectorAll("#tablaCanjes tbody tr"));

  document.addEventListener("DOMContentLoaded", function () {
    const filas = document.querySelectorAll("#tablaCanjes tbody tr");
    const recompensas = [];
    const totalCanjes = [];
    const completados = [];

    filas.forEach((fila, i) => {
if (fila.querySelectorAll("td").length >= 4) {
        const celdas = fila.querySelectorAll("td");
        recompensas.push(celdas[1].innerText);
        totalCanjes.push(parseInt(celdas[2].innerText));
        completados.push(parseInt(celdas[3].innerText));
      }
    });


    console.log("Recompensas:", recompensas);
    console.log("Total Canjes:", totalCanjes);
    console.log("Completados:", completados);

    const colores = ['#42a5f5', '#66bb6a', '#ff7043', '#ab47bc', '#ffa726', '#26c6da'];

    const opciones = {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: { labels: { font: { size: 14 }, color: '#444' } },
        title: {
          display: true,
          font: { size: 18, weight: 'bold' },
          color: '#1b4f72'
        }
      }
    };

    new Chart(document.getElementById("graficoBarras"), {
      type: 'bar',
      data: {
        labels: recompensas,
        datasets: [{
          label: 'Total Canjes',
          data: totalCanjes,
          backgroundColor: colores,
          borderRadius: 6
        }]
      },
      options: {
        ...opciones,
        plugins: {
          ...opciones.plugins,
          title: { ...opciones.plugins.title, text: '🎁 Total de Canjes por Recompensa' }
        }
      }
    });

    new Chart(document.getElementById("graficoLinea"), {
      type: 'line',
      data: {
        labels: recompensas,
        datasets: [{
          label: 'Canjes Completados',
          data: completados,
          borderColor: '#8e24aa',
          backgroundColor: 'rgba(158, 39, 176, 0.2)',
          fill: true,
          tension: 0.3,
          pointBackgroundColor: colores,
          pointRadius: 6
        }]
      },
      options: {
        ...opciones,
        plugins: {
          ...opciones.plugins,
          title: { ...opciones.plugins.title, text: '✅ Canjes Completados por Recompensa' }
        }
      }
    });

    new Chart(document.getElementById("graficoPastel"), {
      type: 'pie',
      data: {
        labels: recompensas,
        datasets: [{
          label: 'Distribución de Canjes',
          data: totalCanjes,
          backgroundColor: colores,
          borderColor: '#fff',
          borderWidth: 2
        }]
      },
      options: {
        ...opciones,
        plugins: {
          ...opciones.plugins,
          title: { ...opciones.plugins.title, text: '📊 Distribución de Canjes (%)' }
        }
      }
    });
  });
</script>




        <?php elseif (isset($_POST['generar_reporte'])): ?>
            <p>No se encontraron registros para ese rango de fechas.</p>
        <?php endif; ?>
        <div class="back-button-container">
            <a href="../../panelAdministrativo.php" class="back-button">Anterior</a>
        </div>


        
    </div>

</body>

</html>
