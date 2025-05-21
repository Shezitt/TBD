<?php
    
    require_once("../conexion.php");
    $stmt = $conn->prepare("SELECT * FROM Promocion WHERE activo = 1;");
    
    if ($stmt) {
        $stmt->execute();
        $resultado = $stmt->get_result();
        $stmt->close();
    } else {
        echo "Error al preparar la consulta: " . $conn->error;
        $resultado = false; 
    }
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestionar Promociones</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"> <style>
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

        h2 {
            font-size: 20px;
            color: #007bff; 
            margin-top: 0;
            margin-bottom: 20px;
        }

        .add-promotion-form {
            display: flex;
            flex-wrap: wrap; 
            gap: 15px; 
            margin-bottom: 30px;
            align-items: flex-end; 
        }

        .add-promotion-form input[type="text"],
        .add-promotion-form input[type="number"] {
            flex: 1; 
            min-width: 150px; 
            padding: 10px 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
            background-color: #e0f2f7; 
            color: #333;
            box-shadow: inset 0 1px 3px rgba(0,0,0,0.05);
        }

        .add-promotion-form .date-input-group {
            display: flex;
            align-items: center;
            gap: 5px;
        }
        
        .add-promotion-form label {
            font-size: 14px;
            color: #555;
            white-space: nowrap; 
        }

        .add-promotion-form input[type="date"] {
            padding: 10px 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
            background-color: #e0f2f7; 
            color: #333;
            box-shadow: inset 0 1px 3px rgba(0,0,0,0.05);
        }

        .add-promotion-form input[type="submit"] {
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

        .add-promotion-form input[type="submit"]:hover {
            background-color: #0056b3;
        }

        .table-container {
            border: 2px solid #007bff; 
            border-radius: 8px;
            overflow: hidden; 
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        .promotions-table {
            width: 100%;
            border-collapse: collapse; 
            margin: 0; 
        }

        .promotions-table thead th {
            background-color: #007bff; /* Fondo azul para el encabezado de la tabla */
            color: white;
            padding: 12px 15px;
            text-align: left;
            font-size: 16px;
        }

        .promotions-table tbody td {
            padding: 10px 15px;
            border-bottom: 1px solid #ddd; 
            color: #333;
            font-size: 15px;
        }
        
        .promotions-table tbody tr:last-child td {
            border-bottom: none; 
        }

        .promotions-table tbody tr:nth-child(even) {
            background-color: #f2f2f2; /* Color de fondo para filas pares */
        }

        .promotions-table .action-button {
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

        .promotions-table .modify-button {
            background-color: #007bff; /* Azul para modificar */
        }

        .promotions-table .modify-button:hover {
            background-color: #0056b3;
        }

        .promotions-table .delete-button {
            background-color: #dc3545; /* Rojo para eliminar */
        }

        .promotions-table .delete-button:hover {
            background-color: #c82333;
        }

        .back-button-container {
            margin-top: 30px;
            text-align: left; 
        }

        .back-button {
            display: inline-block;
            background-color: #34495e; /* Gris oscuro similar al panel admin */
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
            <i class="fas fa-recycle icon"></i> <h1>GESTIONAR PROMOCIONES</h1>
        </div>

        <h2>AGREGAR NUEVA PROMOCION</h2>

        <form action="" method="POST" class="add-promotion-form">
            <input type="text" name="nombre" placeholder="Nombre" required>
            <input type="number" name="multiplicador" placeholder="Multiplicador" step="0.01" required>
            <input type="number" name="nivel_requerido" placeholder="Nivel requerido" required> 
            
            <div class="date-input-group">
                <label for="fecha_inicio">Fecha inicio:</label>
                <input type="date" id="fecha_inicio" name="fecha_inicio" required>
            </div>
            
            <div class="date-input-group">
                <label for="fecha_fin">Fecha fin:</label>
                <input type="date" id="fecha_fin" name="fecha_fin" required>
            </div>
            
            <input type="submit" value="Enviar">
        </form>

        <div class="table-container">
            <table class="promotions-table">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Multiplicador</th>
                        <th>Nivel Requerido</th>
                        <th>Fecha Inicio</th>
                        <th>Fecha Fin</th>
                        <th colspan="2">Opciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        if ($resultado && $resultado->num_rows > 0) {
                            while ($fila = $resultado->fetch_assoc()) {
                                echo '<tr>';
                                echo '<td>' . htmlspecialchars($fila['nombre']) . '</td>';
                                echo '<td>' . htmlspecialchars($fila['multiplicador']) . '</td>';
                                echo '<td>' . htmlspecialchars($fila['nivelRequerido']) . '</td>';
                                echo '<td>' . htmlspecialchars($fila['fechaInicio']) . '</td>';
                                echo '<td>' . htmlspecialchars($fila['fechaFin']) . '</td>';
                                echo '<td><a href="modificar_promocion.php?id=' . htmlspecialchars($fila['id']) . '" class="action-button modify-button">Modificar</a></td>';
                                echo '<td><a href="eliminar_promocion.php?id=' . htmlspecialchars($fila['id']) . '" class="action-button delete-button">Eliminar</a></td>';
                                echo '</tr>';
                            }
                        } else {
                            echo '<tr><td colspan="7">No hay promociones activas.</td></tr>';
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
   
    if (isset($conn)) {
        $conn->close();
    }
?>