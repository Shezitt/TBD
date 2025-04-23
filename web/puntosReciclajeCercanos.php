<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Puntos de reciclaje cercanos</title>
    <style>
        .container {
            max-width: 800px;
            margin: auto;
        }
        .container .header {
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>PUNTOS DE RECICLAJE CERCANOS</h1>
        </div>

        <h2>Ingresa tu ubicación</h2>

        <form action="">
            <input type="number" placeholder="Latitud">
            <br>
            <input type="number" placeholder="Longitud">
            <br>
            <input type="submit" value="Buscar">
        </form>

        <br>

        <div class="cuerpo">

            <table border>
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Latitud</th>
                        <th>Longitud</th>
                        <th>Apertura</th>
                        <th>Cierre</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Nombre punto</td>
                        <td>10.000</td>
                        <td>33.200</td>
                        <td>08:00</td>
                        <td>23:00</td>
                        <td>
                            <a href="">Reciclar</a>
                        </td>
                    </tr>
                </tbody>
            </table>

        </div>

        
        
    </div>  
</body>
</html>