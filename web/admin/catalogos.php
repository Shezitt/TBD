<?php

session_start();

if (!(isset($_SESSION['rol']) && $_SESSION['rol'] == 2)) {
    header("Location: ../index.php");
}
    
require_once("../conexion.php");

$catalogo = false;
if ($stmt = $conn->prepare("CALL sp_getCatalogos();")) {
    $stmt->execute();
    $catalogo = $stmt->get_result();
    $stmt->close();
} else {
    error_log("Error al preparar sp_getCatalogos: " . $conn->error);

}

$recompensa = false;
if ($stmt = $conn->prepare("SELECT * FROM Recompensa WHERE activo='1' ORDER BY idCatalogo;")) {
    $stmt->execute();
    $recompensa = $stmt->get_result();
    $stmt->close();
} else {
    error_log("Error al preparar SELECT * FROM Recompensa: " . $conn->error);
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestionar Catálogos y Recompensas</title>
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
            color: #28a745;
            margin-right: 15px;
        }

        .header h1 {
            font-size: 28px;
            color: #333;
            margin: 0;
        }

        h2 {
            font-size: 20px;
            color: #007bff;
            margin-top: 0;
            margin-bottom: 20px;
        }

        .add-form {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            margin-bottom: 30px;
            align-items: flex-end;
        }

        .add-form input[type="text"],
        .add-form input[type="number"],
        .add-form select {
            flex: 1;
            min-width: 180px;
            padding: 10px 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
            background-color: #e0f2f7;
            color: #333;
            box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.05);
        }

        .add-form input[type="submit"] {
            background-color: #007bff;
            color: white;
            padding: 10px 25px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            font-weight: bold;
            transition: background-color 0.3s ease;
        }

        .add-form input[type="submit"]:hover {
            background-color: #0056b3;
        }

        .table-container {
            border: 2px solid #007bff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
        }

        .catalog-table,
        .reward-table {
            width: 100%;
            border-collapse: collapse;
            margin: 0;
        }

        .catalog-table thead th,
        .reward-table thead th {
            background-color: #007bff;
            color: white;
            padding: 12px 15px;
            text-align: left;
            font-size: 16px;
        }

        .catalog-table tbody td,
        .reward-table tbody td {
            padding: 10px 15px;
            border-bottom: 1px solid #ddd;
            color: #333;
            font-size: 15px;
        }

        .catalog-table tbody tr:last-child td,
        .reward-table tbody tr:last-child td {
            border-bottom: none;
        }

        .catalog-table tbody tr:nth-child(even),
        .reward-table tbody tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        .action-button {
            display: inline-block;
            padding: 8px 12px;
            border-radius: 4px;
            text-decoration: none;
            font-size: 14px;
            font-weight: bold;
            color: white;
            transition: background-color 0.3s ease;
            margin-right: 5px;
        }

        .modify-button {
            background-color: #007bff;
        }

        .modify-button:hover {
            background-color: #0056b3;
        }

        .delete-button {
            background-color: #dc3545;
        }

        .delete-button:hover {
            background-color: #c82333;
        }

        .back-button-container {
            margin-top: 30px;
            text-align: left;
        }

        .back-button {
            display: inline-block;
            background-color: #34495e;
            color: white;
            padding: 12px 25px;
            border-radius: 5px;
            text-decoration: none;
            font-size: 16px;
            font-weight: bold;
            transition: background-color 0.3s ease;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .back-button:hover {
            background-color: #2c3e50;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <i class="fas fa-box-open icon"></i>
            <h1>GESTIONAR CATÁLOGOS Y RECOMPENSAS</h1>
        </div>

        <h2>AGREGAR NUEVO CATÁLOGO</h2>

        <form action="" method="POST" class="add-form">
            <input type="text" name="nombre_catalogo" placeholder="Nombre" required>
            <input name="agregar_catalogo" type="submit" value="Enviar">
        </form>

        <div class="table-container">
            <table class="catalog-table">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th colspan="2">Opciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($catalogo && $catalogo->num_rows > 0) {
                        while ($fila = $catalogo->fetch_assoc()) {
                            echo '<tr>';
                            echo '<td>' . htmlspecialchars($fila['nombreCatalogo'] ?? '') . '</td>';
                            echo '<td><a href="modificar_catalogo.php?id=' . htmlspecialchars($fila['idCatalogo'] ?? '') . '" class="action-button modify-button">Modificar</a></td>';
                            echo '<td><a href="eliminar_catalogo.php?id=' . htmlspecialchars($fila['idCatalogo'] ?? '') . '" class="action-button delete-button">Eliminar</a></td>';
                            echo '</tr>';
                        }
                    } else {
                        echo '<tr><td colspan="3">No hay catálogos activos.</td></tr>';
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <h2>AGREGAR NUEVA RECOMPENSA</h2>

        <form action="" method="POST" class="add-form">
            <input type="text" name="nombre_recompensa" placeholder="Nombre" required>
            <input type="number" name="puntos_necesarios" placeholder="Puntos necesarios" step="0.01" required>
            <input type="number" name="nivel_requerido" placeholder="Nivel requerido" required>
            <select name="id_catalogo" required>
                <option value="">Seleccione un Catálogo</option>
                <?php
                if ($stmt = $conn->prepare("SELECT idCatalogo, nombreCatalogo FROM Catalogo WHERE activo='1';")) {
                    $stmt->execute();
                    $catalogo2 = $stmt->get_result();
                    $stmt->close();

                    if ($catalogo2->num_rows > 0) {
                        while ($fila = $catalogo2->fetch_assoc()) {
                            echo "<option value='" . htmlspecialchars($fila['idCatalogo'] ?? '') . "'>" . htmlspecialchars($fila['nombreCatalogo'] ?? '') . "</option>";
                        }
                    } else {
                        echo "<option value=''>No hay catálogos disponibles</option>";
                    }
                } else {
                    error_log("Error al preparar SELECT * FROM Catalogo para el select: " . $conn->error);
                    echo "<option value=''>Error al cargar catálogos</option>";
                }
                ?>
            </select>
            <input type="submit" value="Enviar">
        </form>

        <div class="table-container">
            <table class="reward-table">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Puntos necesarios</th>
                        <th>Nivel requerido</th>
                        <th>Catálogo</th>
                        <th colspan="2">Opciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($recompensa && $recompensa->num_rows > 0) {
                        while ($fila = $recompensa->fetch_assoc()) {
                            echo '<tr>';
                            echo '<td>' . htmlspecialchars($fila['nombre'] ?? '') . '</td>';
                            echo '<td>' . htmlspecialchars($fila['puntosNecesarios'] ?? '') . '</td>';
                            echo '<td>' . htmlspecialchars($fila['nivelRequerido'] ?? '') . '</td>';
                            $nombreCatalogo = 'N/A';
                            $idCatalogoRecompensa = $fila['idCatalogo'] ?? null;

                            if ($idCatalogoRecompensa !== null && $stmt = $conn->prepare("SELECT nombreCatalogo FROM Catalogo WHERE idCatalogo = ?;")) {
                                $stmt->bind_param("i", $idCatalogoRecompensa);
                                $stmt->execute();
                                $resultadoNombreCatalogo = $stmt->get_result();
                                if ($resultadoNombreCatalogo->num_rows > 0) {
                                    $catalogoData = $resultadoNombreCatalogo->fetch_assoc();
                                    $nombreCatalogo = htmlspecialchars($catalogoData['nombreCatalogo'] ?? '');
                                }
                                $stmt->close();
                            }
                            echo '<td>' . $nombreCatalogo . '</td>';
                            echo '<td><a href="modificar_recompensa.php?id=' . htmlspecialchars($fila['id'] ?? '') . '" class="action-button modify-button">Modificar</a></td>';
                            echo '<td><a href="eliminar_recompensa.php?id=' . htmlspecialchars($fila['id'] ?? '') . '" class="action-button delete-button">Eliminar</a></td>';
                            echo '</tr>';
                        }
                    } else {
                        echo '<tr><td colspan="6">No hay recompensas activas.</td></tr>';
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <div class="back-button-container">
            <a href="../panelAdministrativo.php" class="back-button">Anterior</a>
        </div>
    </div>  
</body>

</html>

<?php
    if (isset($_POST['agregar_catalogo'])) {
        $nombre_catalogo = $_POST['nombre_catalogo'];
        
        $stmt = $conn->prepare("CALL sp_nuevoCatalogo(?);");
        $stmt->bind_param("s", $nombre_catalogo);
        $stmt->execute();
        $stmt->close();
        echo "<script>window.location.href = window.location.pathname;</script>";

    }
?>