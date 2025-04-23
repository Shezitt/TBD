<?php
    require_once "conexion.php";
    session_start();
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
        <h1>INICIAR SESIÓN</h1>
        <form method="POST">
            <input type="text" name="username" placeholder="Nombre de usuario"> <br>
            <input type="password" name="password" placeholder="Contraseña"> <br>
            <input type="submit" name="iniciarSesion" value="Ingresar"> <br>
            <a href="registrarse.php">¿No tienes cuenta?</a>
        </form>
    </div>
</body>
</html>

<?php
    
    if ($_POST['iniciarSesion']) {
        
        $username = $_POST['username'];
        $password = $_POST['password'];

        $conn = Conexion::getConexion();
        $stmt = $conn->prepare("CALL sp_verificarUsuario(?, ?);");
        $stmt->bind_param("ss", $username, $password);
        $stmt->execute();
        $resultado = $stmt->get_result();    

        if ($resultado->num_rows == 1) {
            $_SESSION['username'] = $username;
            $fila = $resultado->fetch_assoc();
            $_SESSION['idUsuario'] = $fila['idUsuario'];
            $_SESSION['rol'] = $fila['idRol'];
            header("Location: index.php");
        } else {
            echo "Credenciales incorrectas.";
        }

    }

?>