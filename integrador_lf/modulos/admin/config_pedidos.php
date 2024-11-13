<style>
    .pedidos-container {
    display: flex;
    flex-direction: column;
    gap: 20px;
    padding: 20px;
}

.pedido {
    border: 1px solid #ddd;
    padding: 10px;
    background-color: #f9f9f9;
    border-radius: 5px;
}

.pedido h3 {
    color: #333;
}

.pedido p {
    margin: 5px 0;
}

.pedido small {
    font-size: 0.9em;
    color: #888;
}

</style>

<?php

// Consulta para obtener todos los pedidos
$sql = "SELECT * FROM pedidos ORDER BY fecha_alta DESC";
$result = $con->query($sql);

if ($result && $result->num_rows > 0) {
    echo "<section class='pedidos-container'>"; // Contenedor para los pedidos

    while ($row = $result->fetch_assoc()) {
        echo "<div class='pedido'>";
        echo "<h3>Pedido de " . htmlspecialchars($row['nombre']) . "</h3>";
        echo "<p><strong>Dirección:</strong> " . htmlspecialchars($row['direccion']) . "</p>";
        echo "<p><strong>Teléfono:</strong> " . htmlspecialchars($row['telefono']) . "</p>";
        echo "<p><strong>Email:</strong> " . htmlspecialchars($row['email']) . "</p>";
        echo "<p><strong>Productos:</strong> " . nl2br(htmlspecialchars($row['productos'])) . "</p>";
        echo "<p><strong>Medio de Pago:</strong> " . htmlspecialchars($row['pago']) . "</p>";
        echo "<p><strong>Fecha de Entrega:</strong> " . date('d-m-Y', strtotime($row['fecha_entrega'])) . "</p>";
        echo "<p><strong>Horario de Entrega:</strong> " . htmlspecialchars($row['horario_entrega']) . "</p>";
        echo "<p><strong>Notas:</strong> " . nl2br(htmlspecialchars($row['notas'])) . "</p>";
        echo "<small>Publicado el: " . date('d-m-Y', strtotime($row['fecha_alta'])) . "</small>";
        echo "</div>";
    }

    echo "</section>"; // Cierra el contenedor de los pedidos

} else {
    echo "<p>No hay pedidos registrados.</p>";
}
?>

