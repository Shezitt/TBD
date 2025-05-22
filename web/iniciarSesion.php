<?php
    require_once "conexion.php";
    session_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión</title>
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
            width: 90%;
            max-width: 400px;
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
        input[type="password"] {
            width: 100%;
            padding: 12px;
            border: none;
            border-radius: 10px;
            background-color: #a9e5e1;
            font-size: 16px;
            outline: none;
        }

        .input-group img {
            position: absolute;
            right: 10px;
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

        a {
            display: block;
            margin-top: 15px;
            color: #444;
            text-decoration: none;
            font-size: 13px;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>INICIAR SESIÓN</h1>
        <form method="POST" action="">
            <div class="input-group">
                <input type="text" name="username" placeholder="Nombre de usuario" required>
            </div>
            <div class="input-group">
                <input type="password" name="password" id="password" placeholder="Contraseña" required>
                <img src="https://cdn-icons-png.flaticon.com/512/709/709612.png" id="togglePassword" alt="Ver contraseña">
            </div>
            <input type="submit" name="iniciarSesion" value="INICIAR SESIÓN">
            <a href="registrarse.php">¿No tienes cuenta?</a>
        </form>
    </div>

    <script>
        const togglePassword = document.getElementById('togglePassword');
        const passwordField = document.getElementById('password');

        togglePassword.addEventListener('click', function () {
            const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordField.setAttribute('type', type);
        });
    </script>
</body>
</html>

<?php
    if (isset($_POST['iniciarSesion']) && $_POST['iniciarSesion']) {
        $username = $_POST['username'];
        $password = $_POST['password'];

        $stmt = $conn->prepare("CALL sp_verificarUsuario(?, ?);");
        $stmt->bind_param("ss", $username, $password);
        $stmt->execute();
        $resultado = $stmt->get_result();    
        $stmt->close();

        if ($resultado->num_rows == 1) {
            $_SESSION['username'] = $username;
            $fila = $resultado->fetch_assoc();
            $_SESSION['idUsuario'] = $fila['idUsuario'];
            $_SESSION['rol'] = $fila['idRol'];

            $stmt = $conn->prepare("SELECT nivel FROM Nivel WHERE idNivel=?;");
            $stmt->bind_param("i", $fila['idNivel']);
            $stmt->execute();
            $resultado = $stmt->get_result();
            $stmt->close();
            $fila = $resultado->fetch_assoc();
            
            $_SESSION['nivel'] = $fila['nivel'];
            header("Location: index.php");
        } else {
            echo "<script>alert('Credenciales incorrectas');</script>";
        }
    }
?>
