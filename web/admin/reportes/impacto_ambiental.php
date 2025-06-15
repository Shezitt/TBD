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
        $stmt = $conn->prepare("CALL sp_reporteMaterialesImpacto(?, ?)");
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
    <title>Reporte Impacto Ambiental</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        /* mismos estilos que la plantilla anterior */
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
            <i class="fas fa-leaf icon"></i>
            <h1>REPORTE IMPACTO AMBIENTAL</h1>
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
                <table>
                    <thead>
                        <tr>
                            <th>Nº</th>
                            <th>Material</th>
                            <th>Total Reciclado (kg)</th>
                            <th>Total Puntos Generados</th>
                            <th>CO₂ Reducido (kg)</th>
                            <th>Agua Ahorrada (L)</th>
                            <th>Energía Ahorrada (kWh)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $contador = 1; 
                        
                        $suma_kg = 0;
                        $suma_puntos = 0;
                        $suma_co2 = 0;
                        $suma_agua = 0;
                        $suma_energia = 0;
                        ?>
                        <?php while ($fila = $reporte_resultado->fetch_assoc()): ?>
                            <?php 
                            $suma_kg += $fila['total_reciclado_kg'];
                            $suma_puntos += $fila['total_puntos'];
                            $suma_co2 += $fila['total_co2_reducido'];
                            $suma_agua += $fila['total_agua_reducido'];
                            $suma_energia += $fila['total_energia_reducido'];
                            ?>
                            <tr>
                                <td><?php echo $contador++; ?></td>
                                <td><?php echo htmlspecialchars($fila['material']); ?></td>
                                <td><?php echo number_format($fila['total_reciclado_kg'], 2); ?></td>
                                <td><?php echo number_format($fila['total_puntos'], 2); ?></td>
                                <td><?php echo number_format($fila['total_co2_reducido'], 2); ?></td>
                                <td><?php echo number_format($fila['total_agua_reducido'], 2); ?></td>
                                <td><?php echo number_format($fila['total_energia_reducido'], 2); ?></td>
                            </tr>
                        <?php endwhile; ?>
                        
                        <tr style="font-weight: bold; background-color: #d9edf7;">
                            <td colspan="2">Totales</td>
                            <td><?php echo number_format($suma_kg, 2); ?></td>
                            <td><?php echo number_format($suma_puntos, 2); ?></td>
                            <td><?php echo number_format($suma_co2, 2); ?></td>
                            <td><?php echo number_format($suma_agua, 2); ?></td>
                            <td><?php echo number_format($suma_energia, 2); ?></td>
                        </tr>
                    </tbody>
                </table>
                <div style="display: flex; flex-wrap: wrap; gap: 20px; justify-content: center;">
  <div style="flex: 1 1 45%; max-width: 45%; min-height: 300px;">
    <canvas id="graficoReciclado"></canvas>
  </div>
  <div style="flex: 1 1 45%; max-width: 45%; min-height: 300px;">
    <canvas id="graficoPuntos"></canvas>
  </div>
  <!-- Aumenta la altura solo de los gráficos CO2 y Anillo -->
<div style="flex: 1 1 45%; max-width: 45%; min-height: 400px;">
  <canvas id="graficoCO2" height="400"></canvas>
</div>
<div style="flex: 1 1 45%; max-width: 45%; min-height: 400px;">
  <canvas id="graficoAnillo" height="400"></canvas>
</div>

</div>


            </div>



        <?php elseif (isset($_POST['generar_reporte'])): ?>
            <p>No se encontraron registros para ese rango de fechas.</p>
        <?php endif; ?>

        <div class="back-button-container">
            <a href="../../panelAdministrativo.php" class="back-button">Anterior</a>
        </div>
    </div>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>


<script>
document.addEventListener("DOMContentLoaded", function () {
  const filas = document.querySelectorAll("tbody tr");
  const materiales = [];
  const reciclado = [];
  const puntos = [];
  const co2 = [];
  const totalAgua = <?php echo $suma_agua; ?>;
  const totalEnergia = <?php echo $suma_energia; ?>;

  filas.forEach((fila, i) => {
    if (i < filas.length - 1) {
      const celdas = fila.querySelectorAll("td");
      materiales.push(celdas[1].innerText);
      reciclado.push(parseFloat(celdas[2].innerText));
      puntos.push(parseFloat(celdas[3].innerText));
      co2.push(parseFloat(celdas[4].innerText));
    }
  });

  // 🎨 Colores personalizados por gráfico
  const coloresAzules = ['#bbdefb', '#90caf9', '#64b5f6', '#42a5f5', '#2196f3', '#1e88e5'];
  const coloresLilas = ['#f44336', '#4caf50', '#2196f3', '#ff9800', '#9c27b0', '#00bcd4'];
  const coloresUnicos = ['#f44336', '#4caf50', '#2196f3', '#ff9800', '#9c27b0', '#00bcd4'];
const coloresGalaxia = ['#3f51b5', '#673ab7', '#9c27b0', '#1a237e', '#311b92', '#4a148c']; // agua
const coloresFuego = ['#ff9800', '#f44336', '#ff5722', '#ffc107', '#ff6f00', '#e65100']; // energía
const coloresAgua = [
  '#e0f7fa', // azul muy claro
  '#b2ebf2',
  '#80deea',
  '#4dd0e1',
  '#26c6da',
  '#00bcd4',
  '#00acc1',
  '#0097a7',
  '#00838f',
  '#006064'  // azul más intenso
];

  const opcionesComunes = {
    responsive: true,
    maintainAspectRatio: false,
    layout: { padding: 20 },
    plugins: {
      legend: {
        labels: { color: '#444', font: { size: 14 } }
      },
      tooltip: {
        backgroundColor: '#f9f9f9',
        titleColor: '#333',
        bodyColor: '#666',
        borderColor: '#ccc',
        borderWidth: 1
      },
      title: {
        display: true,
        font: { size: 18, weight: 'bold' },
        color: '#154360',
        padding: { top: 10, bottom: 20 }
      }
    }
  };

  // 📊 Gráfico de barras - Reciclado
  new Chart(document.getElementById("graficoReciclado"), {
    type: 'bar',
    data: {
      labels: materiales,
      datasets: [{
        label: 'Kg Reciclados',
        data: reciclado,
        backgroundColor: coloresAzules.slice(0, materiales.length),
        borderRadius: 8
      }]
    },
    options: {
      ...opcionesComunes,
      plugins: {
        ...opcionesComunes.plugins,
        title: {
          ...opcionesComunes.plugins.title,
          text: '♻️ Materiales Reciclados (kg)'
        }
      }
    }
  });

  // 🌱 Gráfico de línea - CO₂
  new Chart(document.getElementById("graficoCO2"), {
    type: 'line',
    data: {
      labels: materiales,
      datasets: [{
        label: 'CO₂ Reducido (kg)',
        data: co2,
        borderColor: '#8e24aa',
        backgroundColor: 'rgba(158, 39, 176, 0.2)',
        tension: 0.4,
        fill: true,
        pointBackgroundColor: coloresLilas.slice(0, materiales.length),
        pointRadius: 6
      }]
    },
    options: {
    ...opcionesComunes,
    plugins: {
      ...opcionesComunes.plugins,
      title: {
        ...opcionesComunes.plugins.title,
        text: '🌿 CO₂ Reducido por Material'
      },
      legend: {
        display: true,
        position: 'bottom',
        labels: {
          generateLabels: function (chart) {
            return chart.data.labels.map((material, i) => ({
              text: material,
              fillStyle: coloresLilas[i % coloresLilas.length],
              strokeStyle: '#fff',
              lineWidth: 2,
              index: i
            }));
          },
          color: '#333',
          font: { size: 13 }
        }
      }
    }
  }
});

  // 🏆 Gráfico de pastel - Puntos
  new Chart(document.getElementById("graficoPuntos"), {
    type: 'pie',
    data: {
      labels: materiales,
      datasets: [{
        label: 'Puntos',
        data: puntos,
        backgroundColor: coloresUnicos.slice(0, materiales.length),
        borderColor: '#fff',
        borderWidth: 2
      }]
    },
    options: {
      ...opcionesComunes,
      plugins: {
        ...opcionesComunes.plugins,
        title: {
          ...opcionesComunes.plugins.title,
          text: '🏆 Distribución de Puntos por Material'
        }
      }
    }
  });

  // 🌀 Gráfico combinado - Agua y Energía Ahorrada por Material
const aguaPorMaterial = [];
const energiaPorMaterial = [];

filas.forEach((fila, i) => {
  if (i < filas.length - 1) {
    const celdas = fila.querySelectorAll("td");
    aguaPorMaterial.push(parseFloat(celdas[5].innerText));
    energiaPorMaterial.push(parseFloat(celdas[6].innerText));
  }
});

// 🎨 Nuevos colores y duplicación de etiquetas para leyenda personalizada
const etiquetasPersonalizadas = materiales.map(m => `💧 Agua - ${m}`).concat(materiales.map(m => `⚡ Energía - ${m}`));

new Chart(document.getElementById("graficoAnillo"), {
  type: 'doughnut',
  data: {
    labels: etiquetasPersonalizadas,
    datasets: [
      {
        label: 'Agua Ahorrada',
        data: aguaPorMaterial.concat(Array(materiales.length).fill(0)),
        backgroundColor: coloresAgua.slice(0, materiales.length).concat(Array(materiales.length).fill('transparent')),
        borderWidth: 1
      },
      {
        label: 'Energía Ahorrada',
        data: Array(materiales.length).fill(0).concat(energiaPorMaterial),
        backgroundColor: Array(materiales.length).fill('transparent').concat(coloresFuego.slice(0, materiales.length)),
        borderWidth: 1
      }
    ]
  },
  options: {
    ...opcionesComunes,
    cutout: '60%',
    rotation: -90,
    circumference: 180,
    plugins: {
      ...opcionesComunes.plugins,
      title: {
        ...opcionesComunes.plugins.title,
        text: '🌊💡  Agua  | Energía Reducido por Material'
      },
      legend: {
  display: true,
  position: 'bottom',
  labels: {
    generateLabels: function (chart) {
      const datasets = chart.data.datasets;
      const labels = chart.data.labels;
      return labels.map((label, i) => {
        const datasetIndex = i < materiales.length ? 0 : 1;
        const color = datasets[datasetIndex].backgroundColor[i];
        return {
          text: label,
          fillStyle: color,
          strokeStyle: '#fff',
          lineWidth: 2,
          hidden: false,
          index: i
        };
      });
    },
    color: '#333',
    font: { size: 11 } // <-- más pequeño que el original (13)
  }
}
    }
  }
});



});
</script>


</body>

</html>
