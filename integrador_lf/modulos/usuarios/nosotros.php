<section class="presentacion">
    <div class="contenido-presentacion">
        <h2>Conoce a los Dueños de La Fournier</h2>
        
        <div class="galeria">
            <?php
            // Obtener las imágenes
            $sql_images_cliente = "SELECT imagen FROM imagenes_nosotros ORDER BY fecha_alta DESC";
            $result_images_cliente = $con->query($sql_images_cliente);

            if ($result_images_cliente && $result_images_cliente->num_rows > 0) {
                while ($img = $result_images_cliente->fetch_assoc()) {
                    echo "<img src='./imagenes/nosotros/" . htmlspecialchars($img['imagen']) . "' alt='Familia LA FOURNIER' class='galeria-img'>";
                }
            } else {
                echo "<p>No hay imágenes disponibles.</p>";
            }
            ?>
        </div>

        <div class="texto-nosotros">
            <?php
            // Obtener el texto "Nosotros"
            $sql_texto = "SELECT texto FROM nosotros LIMIT 1";
            $result_texto = $con->query($sql_texto);

            if ($result_texto && $result_texto->num_rows > 0) {
                $row_texto = $result_texto->fetch_assoc();
                echo "<p>" . nl2br(htmlspecialchars($row_texto['texto'])) . "</p>";
            } else {
                echo "<p>No hay información disponible sobre los dueños en este momento.</p>";
            }
            ?>
        </div>
    </div>
</section>

<script src="scripts/nos.js"></script>
