<?php
session_start();

if (!in_array("dashboard", $_SESSION['permisos']) or !in_array("dashboard_gestion", $_SESSION['permisos'])) {
    header("Location: index.php");
    exit();
}

require_once("../conexion.php");

if (!isset($_GET['id'])) {
    header("Location: catalogos.php");
}

$id = $_GET['id'];
$recompensa = false;

// Obtener datos de la recompensa
if ($stmt = $conn->prepare("SELECT * FROM Recompensa WHERE idRecompensa = ? AND activo='1'")) {
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $resultado = $stmt->get_result();
    $recompensa = $resultado->fetch_assoc();
    $stmt->close();
} else {
    error_log("Error al preparar SELECT recompensa: " . $conn->error);
}

// Obtener catálogos disponibles
$catalogos = false;
if ($stmt = $conn->prepare("SELECT idCatalogo, nombreCatalogo FROM Catalogo WHERE activo='1'")) {
    $stmt->execute();
    $catalogos = $stmt->get_result();
    $stmt->close();
} else {
    error_log("Error al preparar SELECT catalogos: " . $conn->error);
}

// Procesar formulario
if (isset($_POST['modificar_recompensa'])) {
    $nombre = $_POST['nombre'];
    $puntos = $_POST['puntos_necesarios'];
    $nivel = $_POST['nivel_requerido'];
    $idCatalogo = $_POST['id_catalogo'];

    if ($stmt = $conn->prepare("UPDATE Recompensa SET nombre = ?, puntosNecesarios = ?, nivelRequerido = ?, idCatalogo = ? WHERE idRecompensa = ?")) {
        $stmt->bind_param("sddii", $nombre, $puntos, $nivel, $idCatalogo, $id);
        if ($stmt->execute()) {
            header("Location: catalogos.php");
        } else {
            echo "<script>alert('Error al actualizar la recompensa.');</script>";
        }
        $stmt->close();
    } else {
        error_log("Error al preparar UPDATE recompensa: " . $conn->error);
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Modificar Recompensa</title>
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
            max-width: 700px;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
            padding: 30px;
            margin-top: 50px;
        }
        h1 {
            color: #007bff;
            margin-bottom: 30px;
            font-size: 26px;
        }
        form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }
        input[type="text"],
        input[type="number"],
        select {
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 16px;
            background-color: #eaf6fb;
        }
        input[type="submit"] {
            background-color: #007bff;
            color: white;
            padding: 12px;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #0056b3;
        }
        .back-button {
            display: inline-block;
            margin-top: 20px;
            background-color: #34495e;
            color: white;
            padding: 10px 20px;
            border-radius: 6px;
            text-decoration: none;
            font-size: 16px;
        }
        .back-button:hover {
            background-color: #2c3e50;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Modificar Recompensa</h1>

        <?php if ($recompensa): ?>
        <form method="POST">
            <input type="text" name="nombre" value="<?php echo htmlspecialchars($recompensa['nombre']); ?>" required>
            <input type="number" step="0.01" name="puntos_necesarios" value="<?php echo htmlspecialchars($recompensa['puntosNecesarios']); ?>" required>
            <input type="number" name="nivel_requerido" value="<?php echo htmlspecialchars($recompensa['nivelRequerido']); ?>" required>
            <select name="id_catalogo" required>
                <option value="">Seleccione un catálogo</option>
                <?php
                if ($catalogos && $catalogos->num_rows > 0) {
                    while ($fila = $catalogos->fetch_assoc()) {
                        $selected = ($fila['idCatalogo'] == $recompensa['idCatalogo']) ? 'selected' : '';
                        echo "<option value='" . htmlspecialchars($fila['idCatalogo']) . "' $selected>" . htmlspecialchars($fila['nombreCatalogo']) . "</option>";
                    }
                } else {
                    echo "<option value=''>No hay catálogos disponibles</option>";
                }
                ?>
            </select>
            <input type="submit" name="modificar_recompensa" value="Guardar Cambios">
        </form>
        <?php else: ?>
            <p>No se encontró la recompensa solicitada.</p>
        <?php endif; ?>

        <a href="catalogos.php" class="back-button">Volver</a>
    </div>
</body>
</html>
