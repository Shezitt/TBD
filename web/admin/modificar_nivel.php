<?php
session_start();

if (!in_array("dashboard", $_SESSION['permisos']) or !in_array("dashboard_gestion", $_SESSION['permisos'])) {
    header("Location: index.php");
    exit();
}

require_once("../conexion.php");

if (!isset($_GET['id'])) {
    header("Location: niveles.php");
    exit();
}

$idNivel = $_GET['id'];

$stmt = $conn->prepare("SELECT * FROM Nivel WHERE idNivel = ?");
$stmt->bind_param("i", $idNivel);
$stmt->execute();
$resultado = $stmt->get_result();
$nivel = $resultado->fetch_assoc();
$stmt->close();

if (!$nivel) {
    echo "<script>alert('Nivel no encontrado.'); window.location.href='niveles.php';</script>";
    exit();
}

if (isset($_POST['modificar_nivel'])) {
    $nivel_num = $_POST['nivel'];
    $nombre = $_POST['nombre'];
    $puntosTotalesNecesarios = $_POST['puntosTotalesNecesarios'];

    $stmt = $conn->prepare("UPDATE Nivel SET nivel = ?, nombre = ?, puntosTotalesNecesarios = ? WHERE idNivel = ?");
    $stmt->bind_param("isdi", $nivel_num, $nombre, $puntosTotalesNecesarios, $idNivel);
    $stmt->execute();
    $stmt->close();

    echo "<script>alert('Nivel modificado exitosamente.'); window.location.href='niveles.php';</script>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Modificar Nivel</title>
    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"
    />
    <style>
        /* Mismo CSS que materiales.php */
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
            max-width: 600px;
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
            color: #4caf50;
            margin-right: 15px;
        }

        .header h1 {
            font-size: 28px;
            color: #333;
            margin: 0;
        }

        h2 {
            font-size: 22px;
            color: #007bff;
            margin-top: 0;
            margin-bottom: 20px;
        }

        form input[type="text"],
        form input[type="number"] {
            width: 100%;
            padding: 10px 15px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
            background-color: #e0f2f7;
            color: #333;
        }

        form input[type="submit"] {
            background-color: #007bff;
            color: white;
            padding: 12px 25px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            font-weight: bold;
            transition: background-color 0.3s ease;
        }

        form input[type="submit"]:hover {
            background-color: #0056b3;
        }

        .back-button-container {
            margin-top: 25px;
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
        }

        .back-button:hover {
            background-color: #2c3e50;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <i class="fas fa-pencil-alt icon"></i>
            <h1>MODIFICAR NIVEL</h1>
        </div>

        <h2>Editar datos del nivel</h2>

        <form method="POST">
            <label for="nivel">Nivel (número)</label>
            <input
                type="number"
                id="nivel"
                name="nivel"
                value="<?php echo htmlspecialchars($nivel['nivel']); ?>"
                required
            />

            <label for="nombre">Nombre</label>
            <input
                type="text"
                id="nombre"
                name="nombre"
                value="<?php echo htmlspecialchars($nivel['nombre']); ?>"
                required
            />

            <label for="puntosTotalesNecesarios">Puntos Totales Necesarios</label>
            <input
                type="number"
                id="puntosTotalesNecesarios"
                name="puntosTotalesNecesarios"
                step="0.01"
                value="<?php echo htmlspecialchars($nivel['puntosTotalesNecesarios']); ?>"
                required
            />

            <input type="submit" name="modificar_nivel" value="Guardar Cambios" />
        </form>

        <div class="back-button-container">
            <a href="niveles.php" class="back-button">Cancelar</a>
        </div>
    </div>
</body>

</html>
