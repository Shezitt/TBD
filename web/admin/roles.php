<?php
session_start();

if (!in_array("dashboard", $_SESSION['permisos']) or !in_array("dashboard_gestion", $_SESSION['permisos'])) {
    header("Location: index.php");
    exit();
}

require_once("../conexion.php");

$stmt_roles = $conn->prepare("SELECT * FROM Rol;");
$stmt_roles->execute();
$resultado_roles = $stmt_roles->get_result();
$roles = [];
while ($fila = $resultado_roles->fetch_assoc()) {
    $roles[$fila['idRol']] = $fila['nombreRol'];
}
$stmt_roles->close();

$search_username = isset($_GET['username']) ? $_GET['username'] : '';
if ($search_username !== '') {
    $stmt = $conn->prepare("SELECT * FROM Usuario WHERE username LIKE CONCAT('%', ?, '%');");
    $stmt->bind_param("s", $search_username);
} else {
    $stmt = $conn->prepare("SELECT * FROM Usuario;");
}

$stmt->execute();
$resultado = $stmt->get_result();
$stmt->close();

// Cambiar rol (si se envió formulario)
if (isset($_POST['cambiar_rol'])) {
    $idUsuario = $_POST['idUsuario'];
    $nuevoRol = $_POST['nuevoRol'];

    $stmt = $conn->prepare("UPDATE Usuario SET idRol = ? WHERE idUsuario = ?;");
    $stmt->bind_param("ii", $nuevoRol, $idUsuario);
    $stmt->execute();
    $stmt->close();

    echo "<script>window.location.href = window.location.pathname;</script>";
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Gestionar Roles de Usuario</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
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
            width: 95%;
            max-width: 1200px;
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

        .search-form {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
        }

        .search-form input[type="text"] {
            flex: 1;
            padding: 10px 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
            background-color: #e0f2f7;
        }

        .search-form input[type="submit"] {
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            font-weight: bold;
        }

        .search-form input[type="submit"]:hover {
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
            font-size: 16px;
        }

        tbody td {
            padding: 10px 15px;
            border-bottom: 1px solid #ddd;
            font-size: 15px;
        }

        tbody tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        .action-form {
            display: flex;
            gap: 8px;
        }

        .action-form select {
            padding: 5px 10px;
            border-radius: 4px;
            border: 1px solid #ccc;
        }

        .action-form input[type="submit"] {
            background-color: #28a745;
            color: white;
            padding: 6px 12px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            font-weight: bold;
        }

        .action-form input[type="submit"]:hover {
            background-color: #218838;
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
        }

        .back-button:hover {
            background-color: #2c3e50;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <i class="fas fa-users-cog icon"></i>
            <h1>GESTIONAR ROLES DE USUARIOS</h1>
        </div>

        <h2>BUSCAR USUARIO POR NOMBRE DE USUARIO</h2>
        <form method="GET" class="search-form">
            <input type="text" name="username" placeholder="Nombre de usuario..." value="<?php echo htmlspecialchars($search_username); ?>" />
            <input type="submit" value="Buscar" />
        </form>

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Username</th>
                        <th>Nombre</th>
                        <th>Correo</th>
                        <th>Rol</th>
                        <th>Puntos</th>
                        <th>Puntos Totales</th>
                        <th>Fecha Registro</th>
                        <th>Cambiar Rol</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($resultado && $resultado->num_rows > 0) {
                        while ($fila = $resultado->fetch_assoc()) {
                            echo '<tr>';
                            echo '<td>' . htmlspecialchars($fila['username']) . '</td>';
                            echo '<td>' . htmlspecialchars($fila['nombre']) . '</td>';
                            echo '<td>' . htmlspecialchars($fila['correo']) . '</td>';
                            echo '<td>' . htmlspecialchars($roles[$fila['idRol']]) . '</td>';
                            echo '<td>' . htmlspecialchars($fila['puntos']) . '</td>';
                            echo '<td>' . htmlspecialchars($fila['puntosTotal']) . '</td>';
                            echo '<td>' . htmlspecialchars($fila['fechaRegistro']) . '</td>';
                            echo '<td>
                                <form method="POST" class="action-form">
                                    <input type="hidden" name="idUsuario" value="' . $fila['idUsuario'] . '" />
                                    <select name="nuevoRol">';
                            foreach ($roles as $id => $nombreRol) {
                                $selected = ($id == $fila['idRol']) ? 'selected' : '';
                                echo '<option value="' . $id . '" ' . $selected . '>' . htmlspecialchars($nombreRol) . '</option>';
                            }
                            echo '</select>
                                    <input type="submit" name="cambiar_rol" value="Actualizar" />
                                </form>
                                </td>';
                            echo '</tr>';
                        }
                    } else {
                        echo '<tr><td colspan="8">No se encontraron usuarios.</td></tr>';
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
