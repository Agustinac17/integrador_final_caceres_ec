<?php
include 'conexion.php'; 
conectar(); // Asegúrate de que esta función esté correctamente implementada para inicializar $conn o $con

// Consulta SQL para obtener productos
$sql = "SELECT id_producto, nombre, imagen_path, precio FROM producto"; // Verifica que la tabla se llame 'productos'
$result = $con->query($sql); // Usa la variable de conexión correcta ($conn o $con)

// Verifica si hubo un error en la consulta
if ($result === false) {
    // Si hay error en la consulta
    echo json_encode(["error" => "Error en la consulta SQL: " . $con->error]); // Agrega el mensaje de error de la base de datos
    exit;
}

// Crear un array para almacenar los productos
$productos = [];
if ($result->num_rows > 0) {
    // Si hay productos, los agregamos al array
    while ($row = $result->fetch_assoc()) {
        $productos[] = [
            'id_producto' => $row['id_producto'],
            'nombre' => $row['nombre'],
            'imagen_path' => $row['imagen_path'], // Asegúrate de que la ruta sea correcta
            'precio' => (float)$row['precio'] // Asegúrate de que el precio sea un número flotante
        ];
    }
} else {
    // Si no hay productos, retornamos un array vacío o mensaje adecuado
    $productos = [];
}

// Devolver los productos en formato JSON
header('Content-Type: application/json'); // Asegúrate de que se envíe como JSON
echo json_encode($productos);

// Cerrar la conexión
$con->close(); // Usa la variable de conexión correcta ($conn o $con)
?>
