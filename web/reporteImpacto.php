<?php
session_start();
require_once("conexion.php");
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
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


  .graficos-fila {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
    justify-content: center;
    margin-top: 30px;
  }

  .grafico-grande {
    flex: 1 1 400px;
    max-width: 600px;
  }

  .grafico-pequeno {
    display: flex;
    align-items: center;
    gap: 15px;
    flex: 1 1 250px;
    max-width: 300px;
    margin-top: 30px;
  }

  .leyenda {
    list-style: none;
    padding-left: 0;
    font-size: 14px;
  }

  @media screen and (max-width: 900px) {
    canvas {
      width: 100% !important;
      height: auto !important;
    }
  }

  canvas {
    background: #fff;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    border-radius: 11px;
  }

  #graficoAnillo {
    width: 100% !important;
    height: auto !important;
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

      <!-- PRIMERA FILA - Gráficos con tamaño limitado -->
      <div style="display: flex; justify-content: center; gap: 30px; flex-wrap: wrap; margin-top: 20px;">
        <div style="max-width: 420px; width: 100%;">
          <canvas id="graficoReciclado"></canvas>
        </div>
        <div style="max-width: 420px; width: 100%;">
          <canvas id="graficoCO2"></canvas>
        </div>
      </div>



      <!-- SEGUNDA FILA - Gráficos circulares pequeños con leyendas -->
      <div style="display: flex; justify-content: space-evenly; flex-wrap: wrap; margin-top: 40px;">
        <div style="display: flex; align-items: center;">
          <canvas id="graficoAgua" width="180" height="180"></canvas>
          <ul id="leyendaAgua" style="list-style: none; margin-left: 15px; font-size: 13px;"></ul>
        </div>
        <div style="display: flex; align-items: center;">
          <canvas id="graficoEnergia" width="180" height="180"></canvas>
          <ul id="leyendaEnergia" style="list-style: none; margin-left: 15px; font-size: 13px;"></ul>
        </div>
      </div>




      <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
      <script>
        Chart.register(ChartDataLabels);
      </script>

      <script>
        document.addEventListener("DOMContentLoaded", () => {
          const filas = document.querySelectorAll("table tbody tr");
          const materiales = [],
            reciclado = [],
            co2 = [],
            agua = [],
            energia = [];

          filas.forEach(fila => {
            const c = fila.querySelectorAll("td");
            if (c.length === 6) {
              materiales.push(c[1].innerText);
              reciclado.push(parseFloat(c[2].innerText));
              co2.push(parseFloat(c[3].innerText));
              agua.push(parseFloat(c[4].innerText));
              energia.push(parseFloat(c[5].innerText));
            }
          });

          // ... (obtener datos como ya lo haces)

          const coloresGalaxia = ['#3f51b5', '#673ab7', '#9c27b0', '#1a237e', '#311b92', '#4a148c'];
          const tonosAgua = ['#b2ebf2', '#4dd0e1', '#26c6da', '#00acc1', '#00838f'];
          const tonosEnergia = ['#ffd54f', '#ffb300', '#ffa000', '#ff8f00', '#ff6f00'];

          // Reciclado - barras finas con flechas punteadas
          new Chart(document.getElementById("graficoReciclado"), {
            type: 'bar',
            data: {
              labels: materiales,
              datasets: [{
                label: "Reciclaje (kg)",
                data: reciclado,
                backgroundColor: coloresGalaxia,
                barThickness: 18,
                borderRadius: 5
              }]
            },
            options: {
              plugins: {
                title: {
                  display: true,
                  text: '♻️ Reciclaje por Material',
                  font: {
                    size: 18
                  }
                }
              },
              animation: {
                onComplete: (ctx) => {
                  const chart = ctx.chart;
                  const ctx2d = chart.ctx;
                  const meta = chart.getDatasetMeta(0);
                  const puntos = meta.data.map(bar => bar.tooltipPosition());

                  ctx2d.beginPath();
                  ctx2d.setLineDash([4, 4]);
                  ctx2d.moveTo(puntos[0].x, Math.max(puntos[0].y - 25, 10));
                  for (let i = 1; i < puntos.length; i++) {
                    ctx2d.lineTo(puntos[i].x, Math.max(puntos[i].y - 25, 10));
                  }
                  ctx2d.strokeStyle = "#999";
                  ctx2d.stroke();
                  ctx2d.setLineDash([]);

                  puntos.forEach(pos => {
                    const yFlecha = Math.max(pos.y - 25, 10);
                    ctx2d.beginPath();
                    ctx2d.moveTo(pos.x, yFlecha);
                    ctx2d.lineTo(pos.x - 4, yFlecha + 4);
                    ctx2d.lineTo(pos.x + 4, yFlecha + 4);
                    ctx2d.closePath();
                    ctx2d.fillStyle = "#999";
                    ctx2d.fill();
                  });
                }
              },

              scales: {
                y: {
                  beginAtZero: true,
                  grid: {
                    drawBorder: false, // Quita borde del eje Y
                    drawOnChartArea: true,
                    drawTicks: false,
                  },
                  ticks: {
                    // Si quieres ocultar la línea horizontal 0, no la dibujes
                    callback: (value) => value, // mantener etiquetas normales
                  }
                },
                x: {
                  grid: {
                    drawBorder: false,
                    drawOnChartArea: false,
                    drawTicks: false,
                  }
                }
              }
            }
          });

          // CO2 - puntos de colores
          // Gráfico CO₂ restaurado como al principio (línea simple con puntos)
          new Chart(document.getElementById("graficoCO2"), {
            type: 'line',
            data: {
              labels: materiales,
              datasets: [{
                label: 'CO₂ reducido',
                data: co2,
                borderColor: '#4caf50',
                backgroundColor: 'rgba(76, 175, 80, 0.1)',
                pointBackgroundColor: '#388e3c',
                pointRadius: 5,
                tension: 0.4
              }]
            },
            options: {
              plugins: {
                title: {
                  display: true,
                  text: '🌿 CO₂ Reducido por Material',
                  font: {
                    size: 16
                  }
                },
                legend: {
                  display: false
                }
              },
              scales: {
                y: {
                  beginAtZero: true
                }
              }
            }
          });


          // Agua - torta con leyenda de puntos
          new Chart(document.getElementById("graficoAgua"), {
            type: 'pie',
            data: {
              labels: materiales,
              datasets: [{
                data: agua,
                backgroundColor: tonosAgua
              }]
            },
            options: {
              plugins: {
                title: {
                  display: true,
                  text: '💧 Agua Ahorrada'
                },
                legend: {
                  display: false
                }
              }
            }
          });

          // Energía - anillo con tonos fuertes
          new Chart(document.getElementById("graficoEnergia"), {
            type: 'doughnut',
            data: {
              labels: materiales,
              datasets: [{
                data: energia,
                backgroundColor: tonosEnergia
              }]
            },
            options: {
              cutout: '65%',
              plugins: {
                title: {
                  display: true,
                  text: '⚡ Energía Ahorrada'
                },
                legend: {
                  display: false
                }
              }
            }
          });

          // LEYENDA A LA DERECHA CON PUNTOS
          const leyendaAgua = document.getElementById("leyendaAgua");
          const leyendaEnergia = document.getElementById("leyendaEnergia");

          materiales.forEach((mat, i) => {
            const itemAgua = document.createElement("li");
            itemAgua.innerHTML = `<span style="display:inline-block;width:10px;height:10px;background:${tonosAgua[i]};margin-right:6px;border-radius:50%;"></span>${mat}`;
            leyendaAgua.appendChild(itemAgua);

            const itemEnergia = document.createElement("li");
            itemEnergia.innerHTML = `<span style="display:inline-block;width:10px;height:10px;background:${tonosEnergia[i]};margin-right:6px;border-radius:50%;"></span>${mat}`;
            leyendaEnergia.appendChild(itemEnergia);
          });


          // SEGUNDA TABLA - TU IMPACTO
          const tablaUsuario = document.querySelectorAll("table")[1];
          const filasUsuario = tablaUsuario.querySelectorAll("tbody tr");

          const matUsu = [],
            recicladoUsu = [],
            co2Usu = [],
            aguaUsu = [],
            energiaUsu = [];

          filasUsuario.forEach(fila => {
            const c = fila.querySelectorAll("td");
            if (c.length >= 7) {
              matUsu.push(c[1].innerText);
              recicladoUsu.push(parseFloat(c[2].innerText));
              co2Usu.push(parseFloat(c[3].innerText));
              aguaUsu.push(parseFloat(c[4].innerText));
              energiaUsu.push(parseFloat(c[5].innerText));
            }
          });

          // Obtener % impacto personal desde la segunda tabla
          const porcentajeImpacto = [];

          filasUsuario.forEach(fila => {
            const c = fila.querySelectorAll("td");
            if (c.length >= 7) {
              porcentajeImpacto.push(parseFloat(c[6].innerText)); // columna 7: "Tu impacto respecto al total"
            }
          });


          // Gráfico barras reciclaje usuario
          new Chart(document.getElementById("graficoUsuarioReciclado"), {
            type: 'bar',
            data: {
              labels: matUsu,
              datasets: [{
                label: "Reciclaje (kg)",
                data: recicladoUsu,
                backgroundColor: coloresGalaxia,
                barThickness: 18,
                borderRadius: 5
              }]
            },
            options: {
              plugins: {
                title: {
                  display: true,
                  text: '🧍‍♂️ Tu Reciclaje por Material',
                  font: {
                    size: 16
                  }
                }
              },
              scales: {
                y: {
                  beginAtZero: true
                }
              }
            }
          });

          // Gráfico línea CO2 usuario
          new Chart(document.getElementById("graficoUsuarioCO2"), {
            type: 'line',
            data: {
              labels: matUsu,
              datasets: [{
                label: 'CO₂ reducido (kg)',
                data: co2Usu,
                borderColor: '#2e7d32',
                backgroundColor: 'rgba(76, 175, 80, 0.1)',
                pointBackgroundColor: '#388e3c',
                pointRadius: 5,
                tension: 0.4
              }]
            },
            options: {
              plugins: {
                title: {
                  display: true,
                  text: '🌿 Tu CO₂ Reducido por Material',
                  font: {
                    size: 16
                  }
                },
                legend: {
                  display: false
                }
              },
              scales: {
                y: {
                  beginAtZero: true
                }
              }
            }
          });




          const etiquetasPersonalizadas = materiales.map(m => `💧 Agua - ${m}`)
            .concat(materiales.map(m => `⚡ Energía - ${m}`));

          const coloresAgua = ['#81d4fa', '#4fc3f7', '#29b6f6', '#039be5', '#0288d1'];
          const coloresFuego = ['#ffcc80', '#ffb74d', '#ffa726', '#ff9800', '#fb8c00'];
          while (coloresAgua.length < materiales.length) {
            coloresAgua.push('#81d4fa'); // o varía con otros tonos
          }
          while (coloresFuego.length < materiales.length) {
            coloresFuego.push('#ffcc80');
          }

          new Chart(document.getElementById("graficoAnillo"), {
            type: 'doughnut',
            data: {
              labels: etiquetasPersonalizadas,
              datasets: [{
                  label: 'Agua Ahorrada',
                  data: agua.concat(Array(materiales.length).fill(0)),
                  backgroundColor: coloresAgua.slice(0, materiales.length)
                    .concat(Array(materiales.length).fill('transparent')),
                  borderWidth: 1
                },
                {
                  label: 'Energía Ahorrada',
                  data: Array(materiales.length).fill(0).concat(energia),
                  backgroundColor: Array(materiales.length).fill('transparent')
                    .concat(coloresFuego.slice(0, materiales.length)),
                  borderWidth: 1
                }
              ]
            },
            options: {
              cutout: '60%',
              rotation: -90,
              circumference: 180,
              plugins: {
                title: {
                  display: true,
                  text: '🌊💡  Agua y Energía Ahorrada por Material',
                  font: {
                    size: 16
                  }
                },
                legend: {
                  display: true,
                  position: 'bottom',
                  labels: {
                    generateLabels: function(chart) {
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
                    font: {
                      size: 11
                    }
                  }
                }
              }
            }
          });


          new Chart(document.getElementById("graficoImpactoPersonal"), {
            type: 'polarArea',
            data: {
              labels: matUsu,
              datasets: [{
                label: '% de Impacto Personal',
                data: porcentajeImpacto,
                backgroundColor: [
                  '#FF4C4C',
                  '#FFD93D',
                  '#6EEB83',
                  '#38B6FF',
                  '#C77DFF'
                ]

              }]
            },
            options: {
              plugins: {
                title: {
                  display: true,
                  text: '🧭 Tu Impacto Personal (%) por Material',
                  font: {
                    size: 16
                  }
                },
                tooltip: {
                  callbacks: {
                    label: ctx => `${ctx.label}: ${ctx.raw.toFixed(1)} %`
                  }
                },
                legend: {
                  position: 'bottom',
                  labels: {
                    color: '#333',
                    font: {
                      size: 12
                    }
                  }
                },
                datalabels: {
                  color: '#000',
                  font: {
                    weight: 'bold',
                    size: 12
                  },
                  formatter: (value, ctx) => `${value.toFixed(1)}%`,
                  anchor: 'end',
                  align: 'start',
                  offset: 10,
                  clamp: true
                }
              },
              scale: {
                ticks: {
                  beginAtZero: true,
                  callback: val => `${val}%`
                }
              }
            }
          });



        });
      </script>

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
      <!-- GRÁFICOS DE TU IMPACTO -->
      <div style="display: flex; justify-content: center; gap: 30px; flex-wrap: wrap; margin-top: 40px;">
        <div style="max-width: 420px; width: 100%;">
          <canvas id="graficoUsuarioReciclado"></canvas>
        </div>
        <div style="max-width: 420px; width: 100%;">
          <canvas id="graficoUsuarioCO2"></canvas>
        </div>
      </div>
      <!-- NUEVO BLOQUE - Gráficos Anillo e Impacto Personal -->
      <div style="margin-top: 40px; display: flex; justify-content: center; gap: 30px; flex-wrap: wrap;">
        <!-- Anillo Agua y Energía -->
        <div style="max-width: 420px; width: 100%; display: flex; flex-direction: column; align-items: center;">
          <canvas id="graficoAnillo"></canvas>
        </div>

        <!-- Impacto Personal -->
        <div style="max-width: 420px; width: 100%; display: flex; flex-direction: column; align-items: center;">
          <canvas id="graficoImpactoPersonal"></canvas>
        </div>
      </div>








    </div>

    <div class="button-container">
      <button class="btn" onclick="history.back()">← Volver</button>
    </div>
  </div>
</body>

</html>