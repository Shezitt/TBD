<?php
    session_start();
    if ($_SESSION['rol'] != 2) {
        header("Location: index.php");
    }
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Administrativo</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"> <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f2f5;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: flex-start; /* Alinea al inicio verticalmente */
            min-height: 100vh;
        }

        .container {
            width: 90%;
            max-width: 900px;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            padding: 25px 35px;
            margin-top: 30px; /* Margen superior para separarlo un poco */
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
            color: #4CAF50; /* Color verde para el ícono de reciclaje */
            margin-right: 15px;
        }

        .header h1 {
            font-size: 28px;
            color: #333;
            margin: 0;
            flex-grow: 1; /* Permite que el título ocupe el espacio restante */
        }
        
        .header .menu-icon {
            font-size: 28px;
            color: #555;
            cursor: pointer;
            float: right; /* Alinea el ícono de menú a la derecha */
        }

        .admin-info {
            margin-bottom: 30px;
            line-height: 1.6;
            color: #555;
            font-size: 15px;
        }

        .admin-info strong {
            color: #333;
        }

        .button-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); /* Dos columnas responsivas */
            gap: 20px;
            margin-bottom: 30px;
        }

        .button-grid a {
            display: block;
            background-color: #90EE90; /* Verde claro similar al de la imagen */
            color: #333; /* Texto oscuro para contraste */
            padding: 18px 25px;
            border-radius: 5px;
            text-decoration: none;
            font-size: 18px;
            font-weight: bold;
            text-align: center;
            transition: background-color 0.3s ease, transform 0.2s ease;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .button-grid a:hover {
            background-color: #7CFC00; /* Un verde un poco más intenso al pasar el ratón */
            transform: translateY(-2px); /* Pequeño efecto de elevación */
        }

        .logout-button {
            display: inline-block; /* Para que el botón no ocupe todo el ancho */
            background-color: #34495e; /* Un gris oscuro/azul para el botón de cerrar sesión */
            color: #ffffff;
            padding: 12px 25px;
            border-radius: 5px;
            text-decoration: none;
            font-size: 16px;
            font-weight: bold;
            transition: background-color 0.3s ease;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .logout-button:hover {
            background-color: #2c3e50; /* Un tono más oscuro al pasar el ratón */
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <i class="fas fa-recycle icon"></i> 
            <h1>PANEL ADMINISTRATIVO</h1>
            <i class="fas fa-bars menu-icon"></i> </div>

        <div class="admin-info">
            <p><strong>Nombre administrador:</strong> Carlos la Fuente</p>
            <p><strong>Correo:</strong> carloslafuente12@gmail.com</p>
        </div>

        <div class="button-grid">
            <a href="admin/materiales.php">Gestionar Materiales</a>
            <a href="admin/promociones.php">Gestionar Promociones</a>
            <a href="admin/puntos.php">Gestionar Puntos de Reciclaje</a>
            <a href="admin/catalogos.php">Gestionar Catálogos</a>
            <a href="admin/canjes.php">Gestionar Canjes</a>
            <a href="admin/roles.php">Gestionar Roles</a>
        </div>

        <a href="../" class="logout-button">Volver al panel principal</a>
    </div>  
</body>
</html>