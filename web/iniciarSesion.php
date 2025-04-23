<?php
    require_once "conexion.php"; // Asegúrate de que la conexión esté definida aquí.
    session_start();

    if (isset($_POST['iniciarSesion'])) { // Verificar si el formulario ha sido enviado
        $username = $_POST['username'];
        $password = $_POST['password'];

        // Validar si los campos no están vacíos
        if (empty($username) || empty($password)) {
            echo "Por favor, ingrese ambos campos.";
        } else {
            // Preparar la consulta
            $conn = Conexion::getConexion(); // Asegúrate de que esta conexión esté establecida
            $stmt = $conn->prepare("CALL sp_verificarUsuario(?, ?);");
            $stmt->bind_param("ss", $username, $password);
            $stmt->execute();
            $resultado = $stmt->get_result();    

            if ($resultado->num_rows == 1) {
                // Almacenar datos en sesión
                $_SESSION['username'] = $username;
                $_SESSION['idUsuario'] = $resultado->fetch_assoc()['idUsuario'];
                header("Location: index.php"); // Redirigir al index
                exit(); // Asegúrate de que el script termine después del redireccionamiento
            } else {
                echo "Credenciales incorrectas.";
            }

            $stmt->close(); // Cerrar la declaración después de la ejecución
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión</title>
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
            <input type="text" name="username" placeholder="Nombre de usuario" required> <br>
            <input type="password" name="password" placeholder="Contraseña" required> <br>
            <input type="submit" name="iniciarSesion" value="Ingresar"> <br>
            <a href="registrarse.php">¿No tienes cuenta?</a>
        </form>
    </div>
</body>
</html>

