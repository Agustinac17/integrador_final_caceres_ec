<?php
// Iniciar sesión
session_start(); 

// Función para agregar un nuevo pedido
function agregarPedido($nombre, $direccion, $telefono, $email, $pago, $productos, $fecha_entrega, $horario_entrega, $notas) {
    global $con;
    $stmt = $con->prepare("INSERT INTO pedidos (nombre, direccion, telefono, email, pago, productos, fecha_entrega, horario_entrega, notas, fecha_alta) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())");
    $stmt->bind_param("sssssssss", $nombre, $direccion, $telefono, $email, $pago, $productos, $fecha_entrega, $horario_entrega, $notas);
    $stmt->execute();
    $stmt->close();
}

// Procesar el pedido cuando se recibe la petición POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = $_POST['nombre'];
    $direccion = $_POST['direccion'];
    $telefono = $_POST['telefono'];
    $email = $_POST['email'];
    $pago = $_POST['pago'];
    $productos = $_POST['productos'];
    $fecha_entrega = $_POST['fecha_entrega'];
    $horario_entrega = $_POST['horario_entrega'];
    $notas = isset($_POST['notas']) ? $_POST['notas'] : '';

    // Agregar el pedido a la base de datos
    agregarPedido($nombre, $direccion, $telefono, $email, $pago, $productos, $fecha_entrega, $horario_entrega, $notas);

    // Establecer un mensaje de éxito en la sesión
    $_SESSION['mensaje'] = 'Pedido agregado exitosamente.';

    // Redirigir a la misma página para evitar el reenvío del formulario
    header("Location: pedidos.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Pedido</title>
    <style>
        form {
            display: flex;
            flex-direction: column;
            gap: 20px;
            max-width: 500px;
            margin: 40px auto;
            padding: 20px;
            background-color: #9986528e; 
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Sombra suave */
        }

        form div {
            display: flex;
            flex-direction: column;
        }

        label {
            font-size: 16px;
            color: rgb(63, 59, 41);
            margin-bottom: 8px;
        }

        input, select, textarea {
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
            color: rgb(63, 59, 41);
            background-color: #f9f9f9; /* Fondo claro para los campos de entrada */
        }

        textarea {
            resize: vertical; /* Permitir redimensionar verticalmente */
        }

        button {
            padding: 12px;
            background-color: #554b39;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #543261;
        }
    </style>
</head>
<body>

    <?php
    // Mostrar el mensaje si está presente en la sesión
    if (isset($_SESSION['mensaje'])) {
        echo "<p style='color: green;'>" . $_SESSION['mensaje'] . "</p>";
        unset($_SESSION['mensaje']); // Elimina el mensaje después de mostrarlo
    }
    ?>

    <form action="pedidos.php" method="POST">
        <div>
            <label for="nombre">Nombre del Cliente:</label>
            <input type="text" id="nombre" name="nombre" required placeholder="Ingrese su nombre">
        </div>
        <div>
            <label for="direccion">Dirección:</label>
            <input type="text" id="direccion" name="direccion" required placeholder="Ingrese su dirección">
        </div>
        <div>
            <label for="telefono">Teléfono:</label>
            <input type="tel" id="telefono" name="telefono" required placeholder="Ingrese su número de teléfono">
        </div>
        <div>
            <label for="email">E-mail:</label>
            <input type="email" id="email" name="email" required placeholder="Ingrese su e-mail">
        </div>
        <div>
            <label for="pago">Medio de Pago:</label>
            <select id="pago" name="pago" required>
                <option value="tarjeta">Tarjeta de Crédito</option>
                <option value="tarjeta">Tarjeta de Débito</option>
                <option value="mercado_pago">Mercado Pago</option>
                <option value="transferencia">Transferencia</option>
                <option value="efectivo">Efectivo</option>
            </select>
        </div>
        <div>
            <label for="productos">Productos:</label>
            <textarea id="productos" name="productos" rows="4" placeholder="Ingrese los productos deseados" required></textarea>
        </div>
        <div>
            <label for="fecha_entrega">Fecha de Entrega:</label>
            <input type="date" id="fecha_entrega" name="fecha_entrega" required>
        </div>
        <div>
            <label for="horario_entrega">Horario de Entrega:</label>
            <input type="time" id="horario_entrega" name="horario_entrega" required>
        </div>
        <div>
            <label for="notas">Notas Adicionales:</label>
            <textarea id="notas" name="notas" rows="3" placeholder="Ingrese cualquier nota adicional"></textarea>
        </div>
        <button type="submit">Completar Pedido</button>
    </form>

</body>
</html>
