<?php
    require_once("conexion.php");
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrarse</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #fff;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .container {
            max-width: 400px;
            width: 90%;
            text-align: center;
        }

        h1 {
            font-size: 24px;
            color: #0a1a2a;
            margin-bottom: 20px;
            font-weight: bold;
        }

        .input-group {
            position: relative;
            margin-bottom: 15px;
        }

        input[type="text"],
        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 12px;
            border: none;
            border-radius: 12px;
            background-color: #a9e5e1;
            font-size: 16px;
            outline: none;
            box-sizing: border-box;
        }

        .input-group img {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            width: 20px;
            height: 20px;
            cursor: pointer;
        }

        input[type="submit"] {
            width: 100%;
            padding: 14px;
            background-color: #001f2f;
            color: white;
            font-weight: bold;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            margin-top: 10px;
            cursor: pointer;
        }

        .btn-secondary {
            margin-top: 10px;
            background-color: #19708a;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 4px;
            font-size: 14px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
        }

        .btn-secondary:hover {
            background-color: #145c72;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>REGISTRARSE</h1>
        <form action="registrarse.php" method="POST">
            <div class="input-group">
                <input type="text" name="nombre" placeholder="Nombre" required>
            </div>
            <div class="input-group">
                <input type="text" name="username" placeholder="Nombre usuario" required>
            </div>
            <div class="input-group">
                <input type="email" name="email" placeholder="Correo electronico" required>
            </div>
            <div class="input-group">
                <input type="password" name="password" id="password" placeholder="Contraseña" required>
                <img src="https://cdn-icons-png.flaticon.com/512/709/709612.png" id="togglePassword1" alt="Ver contraseña">
            </div>
            <div class="input-group">
                <input type="password" name="confirmarPassword" id="confirmarPassword" placeholder="Confirmar contraseña" required>
                <img src="https://cdn-icons-png.flaticon.com/512/709/709612.png" id="togglePassword2" alt="Ver contraseña">
            </div>
            <input name="registrarse" type="submit" value="REGISTRARSE">
        </form>
        <a href="iniciarSesion.php" class="btn-secondary">Anterior</a>
    </div>

    <script>
        const togglePassword1 = document.getElementById('togglePassword1');
        const togglePassword2 = document.getElementById('togglePassword2');
        const password = document.getElementById('password');
        const confirmarPassword = document.getElementById('confirmarPassword');

        togglePassword1.addEventListener('click', () => {
            const type = password.type === 'password' ? 'text' : 'password';
            password.type = type;
        });

        togglePassword2.addEventListener('click', () => {
            const type = confirmarPassword.type === 'password' ? 'text' : 'password';
            confirmarPassword.type = type;
        });
    </script>
</body>
</html>

<?php
    if (isset($_POST['registrarse'])) {
        $nombre = $_POST['nombre'];
        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $confirmarPass = $_POST['confirmarPassword'];

        if ($password == $confirmarPass) {
            $stmt = $conn->prepare("CALL sp_verificarUsernameEmailUnico(?, ?)");
            $stmt->bind_param("ss", $username, $email);
            $stmt->execute();
            $resultado = $stmt->get_result();
            $stmt->close();

            $resultado = $resultado->fetch_assoc();
        
            if ($resultado['respuesta'] == '1') {
                $stmt = $conn->prepare("CALL sp_registrarUsuario(?, ?, ?, ?);");
                $stmt->bind_param("ssss", $nombre, $username, $email, $password);
                $stmt->execute();
                $stmt->close();

                echo "<script>alert('Cuenta creada correctamente.'); window.location.href = 'iniciarSesion.php';</script>";
            } else {
                echo "<script>alert('Nombre de usuario o correo electrónico no son únicos.');</script>";
            }
        } else {
            echo "<script>alert('Las contraseñas no coinciden.');</script>";
        }
    }
?>
