<?php
session_start();


require_once("../../conexion.php");

$mensaje = "";
$usuarioBuscado = "";
$reciclajes = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (isset($_POST['buscar_usuario'])) {
        $usuarioBuscado = trim($_POST['usuario']);

        if ($usuarioBuscado !== '') {
            $stmt = $conn->prepare("
                SELECT r.idRegistro, r.fecha, r.puntosGanados, u.username, m.nombre AS nombreMaterial, r.cantidad, p.nombre AS nombrePunto
                FROM Registro_Reciclaje r
                JOIN Punto_Reciclaje p ON p.idPunto = r.idPunto
                JOIN Usuario u ON r.idUsuario = u.idUsuario
                JOIN Material m ON m.idMaterial = r.idMaterial
                WHERE u.username = ?
                ORDER BY r.fecha DESC
            ");
            $stmt->bind_param("s", $usuarioBuscado);
            $stmt->execute();
            $resultado = $stmt->get_result();
            $reciclajes = $resultado->fetch_all(MYSQLI_ASSOC);
            $stmt->close();
            if (empty($reciclajes)) {
                $mensaje = "No se encontraron reciclajes para el usuario '$usuarioBuscado'.";
            }
        } else {
            $mensaje = "Por favor, ingresa un nombre de usuario.";
        }
    }

    // Para modificar puntos ganados
    if (isset($_POST['modificar_puntos'])) {
        $idReciclaje = intval($_POST['idReciclaje']);
        $idAdmin = $_SESSION['idUsuario']; 
        $nuevoPuntos = floatval($_POST['nuevoPuntos']);
        $motivo = trim($_POST['motivo']);
        $usuarioBuscado = trim($_POST['usuarioActual']);

        if ($motivo === '') {
            $mensaje = "Debes justificar la modificación.";
        } else {
            $stmt = $conn->prepare("CALL ActualizarPuntosGanados(?, ?, ?, ?);");
            $stmt->bind_param("iids", $idReciclaje, $idAdmin, $nuevoPuntos, $motivo);
            if ($stmt->execute()) {
                $mensaje = "Modificación aplicada correctamente.";
            } else {
                $mensaje = "Error al aplicar la modificación: " . $stmt->error;
            }
            $stmt->close();

            // para mantener la tabla actualizada
            if ($usuarioBuscado !== '') {
                $stmt = $conn->prepare("
                    SELECT r.idRegistro, r.fecha, r.puntosGanados, u.username, m.nombre AS nombreMaterial, r.cantidad, p.nombre AS nombrePunto
                    FROM Registro_Reciclaje r
                    JOIN Punto_Reciclaje p ON p.idPunto = r.idPunto
                    JOIN Usuario u ON r.idUsuario = u.idUsuario
                    JOIN Material m ON m.idMaterial = r.idMaterial
                    WHERE u.username = ?
                    ORDER BY r.fecha DESC
                ");
                $stmt->bind_param("s", $usuarioBuscado);
                $stmt->execute();
                $resultado = $stmt->get_result();
                $reciclajes = $resultado->fetch_all(MYSQLI_ASSOC);
                $stmt->close();
            }
        }
    }
}

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Gestionar Puntos Ganados - Reciclajes</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f2f5;
            margin: 0;
            padding: 0;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: flex-start;
        }

        .container {
            width: 90%;
            max-width: 1000px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            padding: 25px 35px;
            margin-top: 30px;
            margin-bottom: 30px;
        }

        h1 {
            color: #4caf50;
            margin-bottom: 20px;
        }

        form {
            margin-bottom: 30px;
        }

        input[type="text"], input[type="number"], textarea {
            padding: 10px 15px;
            border-radius: 5px;
            border: 1px solid #ccc;
            font-size: 16px;
            box-shadow: inset 0 1px 3px rgba(0,0,0,0.05);
            width: 100%;
            box-sizing: border-box;
        }

        input[type="submit"], button {
            background-color: #007bff;
            color: white;
            padding: 10px 25px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
            font-size: 16px;
            transition: background-color 0.3s ease;
            margin-top: 10px;
        }

        input[type="submit"]:hover, button:hover {
            background-color: #0056b3;
        }

        .message {
            margin-bottom: 20px;
            font-weight: bold;
            color: #c0392b;
        }

        .success {
            color: #27ae60;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }

        th, td {
            padding: 12px 15px;
            border-bottom: 1px solid #ddd;
            text-align: left;
        }

        th {
            background-color: #007bff;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        .btn-modificar {
            background-color: #28a745;
            border: none;
            color: white;
            padding: 7px 12px;
            cursor: pointer;
            border-radius: 5px;
            font-size: 14px;
            font-weight: bold;
            transition: background-color 0.3s ease;
        }

        .btn-modificar:hover {
            background-color: #1e7e34;
        }

        .form-modificacion {
            margin-top: 10px;
            background-color: #e9f7ef;
            padding: 15px;
            border-radius: 5px;
            border: 1px solid #27ae60;
        }

        label {
            display: block;
            margin-bottom: 6px;
            font-weight: bold;
            color: #2c3e50;
        }

        textarea {
            resize: vertical;
            min-height: 60px;
        }

        
    </style>

    <script>
        function mostrarFormulario(id) {
            // Ocultar todos los formularios abiertos
            document.querySelectorAll('.form-modificacion').forEach(form => form.style.display = 'none');
            // Mostrar solo el formulario de la fila seleccionada
            const form = document.getElementById('form-modificar-' + id);
            if (form) {
                form.style.display = 'block';
            }
        }
    </script>
</head>

<body>
    <div class="container">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <h1>Gestionar Puntos Ganados - Reciclajes</h1>
            <a href="../../panelAdministrativo.php" style="
                background-color: #6c757d;
                color: white;
                padding: 10px 20px;
                border-radius: 5px;
                text-decoration: none;
                font-weight: bold;
                transition: background-color 0.3s ease;
            " onmouseover="this.style.backgroundColor='#5a6268'" onmouseout="this.style.backgroundColor='#6c757d'">Volver</a>
        </div>

        <?php if ($mensaje !== ""): ?>
            <div class="message <?= strpos($mensaje, 'correctamente') !== false ? 'success' : '' ?>">
                <?= htmlspecialchars($mensaje) ?>
            </div>
        <?php endif; ?>

        <!-- Formulario para buscar usuario -->
        <form method="POST" action="">
            <label for="usuario">Buscar reciclajes por nombre de usuario:</label>
            <input type="text" id="usuario" name="usuario" value="<?= htmlspecialchars($usuarioBuscado) ?>" placeholder="Nombre de usuario" required />
            <input type="submit" name="buscar_usuario" value="Buscar" />
        </form>

        <?php if (!empty($reciclajes)): ?>
            <table>
                <thead>
                    <tr>
                        <th>ID Reciclaje</th>
                        <th>Fecha</th>
                        <th>Usuario</th>
                        <th>Material</th>
                        <th>Cantidad</th>
                        <th>Punto</th>
                        <th>Puntos Ganados</th>
                        <th>Modificar</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($reciclajes as $rec): ?>
                        <tr>
                            <td><?= htmlspecialchars($rec['idRegistro']) ?></td>
                            <td><?= htmlspecialchars($rec['fecha']) ?></td>
                            <td><?= htmlspecialchars($rec['username']) ?></td>
                            <td><?= htmlspecialchars($rec['nombreMaterial']) ?></td>
                            <td><?= htmlspecialchars($rec['cantidad']) ?></td>
                            <td><?= htmlspecialchars($rec['nombrePunto']) ?></td>
                            <td><?= htmlspecialchars($rec['puntosGanados']) ?></td>
                            <td>
                                <button class="btn-modificar" type="button" onclick="mostrarFormulario(<?= $rec['idRegistro'] ?>)">Modificar puntos</button>

                                <form method="POST" action="" class="form-modificacion" id="form-modificar-<?= $rec['idRegistro'] ?>" style="display:none;">
                                    <input type="hidden" name="idReciclaje" value="<?= $rec['idRegistro'] ?>">
                                    <input type="hidden" name="usuarioActual" value="<?= htmlspecialchars($usuarioBuscado) ?>">
                                    <label for="nuevoPuntos-<?= $rec['idRegistro'] ?>">Nuevo valor de puntosGanados:</label>
                                    <input type="number" id="nuevoPuntos-<?= $rec['idRegistro'] ?>" name="nuevoPuntos" step="0.01" min="0" required>

                                    <label for="motivo-<?= $rec['idRegistro'] ?>">Justificación de la modificación:</label>
                                    <textarea id="motivo-<?= $rec['idRegistro'] ?>" name="motivo" required></textarea>

                                    <input type="submit" name="modificar_puntos" value="Aplicar cambio">
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php elseif ($usuarioBuscado !== ''): ?>
            <p>No se encontraron reciclajes para el usuario <?= htmlspecialchars($usuarioBuscado) ?>.</p>
        <?php endif; ?>

        <?php if ($usuarioBuscado !== ''): ?>
        <h2 style="color: #4caf50; margin-top: 40px;">Historial de Modificaciones de Reciclaje</h2>
        <table class="tabla-modificaciones">
            <thead>
                <tr>
                    <th>ID Registro</th>
                    <th>Nombre Admin</th>
                    <th>Modificación</th>
                    <th>Motivo</th>
                    <th>Fecha Modificación</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    $sql = $conn->prepare('CALL ObtenerModificacionReciclaje(?);');
                    $sql->bind_param('s', $usuarioBuscado);  
                    $sql->execute();
                    $result = $sql->get_result();
                    if ($result && $result->num_rows > 0) {
                        while ($rec = $result->fetch_assoc()) {
                            $idr = $rec['idReciclaje'];
                            $nombreAdmin = $rec['nombreAdmin'];
                            $modificacion = $rec['modificacion'];
                            $motivo = $rec['motivo'];
                            $fechaHora = $rec['fechaHora'];
                            echo "<tr>";
                            echo "<td>" . $idr . "</td>";
                            echo "<td>" . $nombreAdmin . "</td>";
                            echo "<td>" . $modificacion . "</td>";
                            echo "<td>" . $motivo . "</td>";
                            echo "<td>" . $fechaHora . "</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo '<tr><td colspan="5" style="text-align:center; color:#999;">No hay modificaciones para mostrar.</td></tr>';
                    }

                ?>
            </tbody>
        </table>
        <?php endif; ?>

    </div>
</body>

</html>
