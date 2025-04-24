<?php
    require_once("conexion.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrarse</title>
    <style>
        .container {
            max-width: 500px;
            margin: auto;
            text-align: center; 
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>REGISTRARSE</h1>
        <form action="registrarse.php" method="POST">
            <input type="text" name="nombre" placeholder="Nombre"> <br>
            <input type="text" name="username" placeholder="Nombre de usuario"> <br>
            <input type="text" name="email" placeholder="Email"> <br>
            <input type="text" name="password" placeholder="Contraseña"> <br>
            <input type="text" name="confirmarPassword" placeholder="Confirmar contraseña"> <br>
            <input name="registrarse" type="submit" value="Registrarse"> <br>
        </form>
    </div>
</body>
</html>

<?php

    if ($_POST['registrarse']) {

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

                echo "Cuenta creada correctamente. Ir a <a href='iniciarSesion.php'>Iniciar sesión</a>";

            } else {
                echo "Nombre de usuario o correo electrónico no son unicos.";
            }
            
        }
        else {
            echo "Las contraseñas no coinciden.";
        }
        

    }

?>