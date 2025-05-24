<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ver puntos de reciclaje</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f0f4f8;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 60px auto;
            padding: 30px;
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0 0 15px rgba(0,0,0,0.08);
            text-align: center;
        }
        .header h1 {
            color: #2c3e50;
            font-size: 32px;
            margin-bottom: 30px;
        }
        .cuerpo {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }
        .cuerpo a {
            display: block;
            background: #27ae60;
            color: #fff;
            padding: 14px 20px;
            text-decoration: none;
            border-radius: 8px;
            font-size: 18px;
            transition: background 0.3s;
        }
        .cuerpo a:hover {
            background: #219150;
        }
        a.volver {
            display: inline-block;
            margin-top: 30px;
            text-decoration: none;
            color: #3498db;
            font-weight: bold;
            transition: 0.3s;
        }
        a.volver:hover {
            color: #21618c;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>VER PUNTOS DE RECICLAJE</h1>
        </div>

        <div class="cuerpo">
            <a href="todosPuntosReciclaje.php">Ver todos los puntos de reciclaje</a>
            <a href="puntosReciclajeCercanos.php">Ver puntos de reciclaje cercanos</a>
        </div>

        <a class="volver" href="index.php">← Volver</a>
    </div>
</body>
</html>
