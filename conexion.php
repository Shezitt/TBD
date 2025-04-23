<?php
// Incluir la conexión a la base de datos
include('db.php');

// Verificar si los datos fueron enviados por el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = $_POST['usuario'];  // Obtener el valor del campo 'usuario'

    if (!empty($usuario)) {
        // Preparar la consulta para insertar el nuevo usuario
        $sql = "INSERT INTO usuarios (nombre) VALUES ('$usuario')";

        // Ejecutar la consulta
        if ($conn->query($sql) === TRUE) {
            echo "Nuevo usuario agregado con éxito";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    } else {
        echo "El campo de usuario no puede estar vacío";
    }

    // Cerrar la conexión
    $conn->close();
}
?>

