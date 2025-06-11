<?php
session_start();

if (!in_array("dashboard", $_SESSION['permisos']) or !in_array("dashboard_gestion", $_SESSION['permisos'])) {
    header("Location: index.php");
    exit();
}

require_once("../conexion.php");

$stmt_roles = $conn->prepare("SELECT * FROM Rol WHERE activo = 1;");
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

// Crear nuevo rol (si se envió formulario)
if (isset($_POST['crear_rol'])) {
    $nuevoNombreRol = trim($_POST['nombreRol']);
    if ($nuevoNombreRol !== '') {
        $stmt = $conn->prepare("INSERT INTO Rol (nombreRol) VALUES (?);");
        $stmt->bind_param("s", $nuevoNombreRol);
        $stmt->execute();
        $stmt->close();

        echo "<script>window.location.href = window.location.pathname;</script>";
    }
}

// Obtener todos los permisos
$stmt_permisos = $conn->prepare("SELECT * FROM Permiso;");
$stmt_permisos->execute();
$resultado_permisos = $stmt_permisos->get_result();
$permisos = [];
while ($fila = $resultado_permisos->fetch_assoc()) {
    $permisos[$fila['idPermiso']] = $fila['nombrePermiso'];
}
$stmt_permisos->close();

// Actualizar permisos del rol seleccionado
if (isset($_POST['actualizar_permisos'])) {
    $idRolSeleccionado = $_POST['idRolSeleccionado'];
    $permisosSeleccionados = isset($_POST['permisos']) ? $_POST['permisos'] : [];

    // Eliminar permisos actuales
    $stmt_delete = $conn->prepare("DELETE FROM rol_has_permiso WHERE Rol_idRol = ?;");
    $stmt_delete->bind_param("i", $idRolSeleccionado);
    $stmt_delete->execute();
    $stmt_delete->close();

    // Insertar permisos seleccionados
    $stmt_insert = $conn->prepare("INSERT INTO rol_has_permiso (Rol_idRol, Permiso_idPermiso) VALUES (?, ?);");
    foreach ($permisosSeleccionados as $idPermiso) {
        $stmt_insert->bind_param("ii", $idRolSeleccionado, $idPermiso);
        $stmt_insert->execute();
    }
    $stmt_insert->close();

    echo "<script>window.location.href = window.location.pathname + '?idRolGestionado=" . $idRolSeleccionado . "';</script>";
}

// Obtener permisos actuales de un rol (si se seleccionó)
$permisos_rol_actual = [];
if (isset($_GET['idRolGestionado'])) {
    $idRolGestionado = $_GET['idRolGestionado'];

    $stmt_actuales = $conn->prepare("SELECT Permiso_idPermiso FROM rol_has_permiso WHERE Rol_idRol = ?;");
    $stmt_actuales->bind_param("i", $idRolGestionado);
    $stmt_actuales->execute();
    $resultado_actuales = $stmt_actuales->get_result();
    while ($fila = $resultado_actuales->fetch_assoc()) {
        $permisos_rol_actual[] = $fila['Permiso_idPermiso'];
    }
    $stmt_actuales->close();
}

// Eliminar rol
if (isset($_POST['eliminar_rol'])) {
    $idRolEliminar = $_POST['idRolEliminar'];

    $stmt = $conn->prepare("UPDATE Rol SET activo = 0 WHERE idRol = ?;");
    $stmt->bind_param("i", $idRolEliminar);
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

        <h2>GESTIONAR PERMISOS DE ROLES</h2>

        <form method="GET" class="search-form">
            <select name="idRolGestionado">
                <option value="">Seleccionar Rol...</option>
                <?php
                foreach ($roles as $id => $nombre) {
                    $selected = (isset($_GET['idRolGestionado']) && $_GET['idRolGestionado'] == $id) ? 'selected' : '';
                    echo "<option value='$id' $selected>$nombre</option>";
                }
                ?>
            </select>
            <input type="submit" value="Gestionar Permisos" />
        </form>

        <?php if (isset($_GET['idRolGestionado']) && $_GET['idRolGestionado'] !== ''): ?>
            <form method="POST" style="margin-top: 20px;">
                <input type="hidden" name="idRolSeleccionado" value="<?php echo $_GET['idRolGestionado']; ?>" />
                <div style="display: flex; flex-wrap: wrap; gap: 10px; margin-bottom: 20px;">
                    <?php foreach ($permisos as $idPermiso => $nombrePermiso): ?>
                        <label style="display: flex; align-items: center; gap: 6px; padding: 8px 12px; background: #f2f2f2; border-radius: 6px;">
                            <input type="checkbox" name="permisos[]" value="<?php echo $idPermiso; ?>"
                                <?php echo in_array($idPermiso, $permisos_rol_actual) ? 'checked' : ''; ?> />
                            <?php echo htmlspecialchars($nombrePermiso); ?>
                        </label>
                    <?php endforeach; ?>
                </div>
                <input type="submit" name="actualizar_permisos" value="Guardar Cambios" style="background-color: #28a745; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; font-size: 16px; font-weight: bold;" />
            </form>
        <?php endif; ?>

        <hr style="margin: 40px 0; border: 0; border-top: 2px solid #007bff;" />

        <h2>BUSCAR USUARIO POR NOMBRE DE USUARIO</h2>
        <form method="GET" class="search-form">
            <input type="text" name="username" placeholder="Nombre de usuario..."
                value="<?php echo htmlspecialchars($search_username); ?>" />
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

        <hr style="margin: 40px 0; border: 0; border-top: 2px solid #007bff;" />

        <h2>GESTIONAR ROLES</h2>

        <!-- Formulario para crear nuevo rol -->
        <form method="POST" style="display: flex; gap: 10px; margin-bottom: 20px;">
            <input type="text" name="nombreRol" placeholder="Nombre del nuevo rol..."
                style="flex:1; padding: 10px 15px; border: 1px solid #ccc; border-radius: 5px; font-size: 16px;"
                required />
            <input type="submit" name="crear_rol" value="Crear Rol"
                style="background-color: #28a745; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; font-size: 16px; font-weight: bold;" />
        </form>

        <!-- Tabla de roles -->
        <div class="table-container">
            <table style="width:100%; border-collapse: collapse;">
                <thead>
                    <tr>
                        <th style="text-align:left; padding:8px;">ID</th>
                        <th style="text-align:left; padding:8px;">Nombre</th>
                        <th style="text-align:left; padding:8px;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($roles as $id => $nombre): ?>
                        <tr>
                            <td style="padding:8px;"><?php echo $id; ?></td>
                            <td style="padding:8px;"><?php echo htmlspecialchars($nombre); ?></td>
                            <td style="padding:8px;">
                                <form method="POST" onsubmit="return confirm('¿Seguro que deseas eliminar este rol?');" style="display:inline-block;">
                                    <input type="hidden" name="idRolEliminar" value="<?php echo $id; ?>">
                                    <input type="submit" name="eliminar_rol" value="Eliminar Rol" style="background-color: #dc3545; color: white; padding: 6px 12px; border: none; border-radius: 4px; cursor: pointer;">
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div class="back-button-container">
            <a href="../panelAdministrativo.php" class="back-button">Anterior</a>
        </div>
    </div>
</body>

</html>