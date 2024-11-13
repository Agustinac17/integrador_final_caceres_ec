<?php include 'includes/conexion.php'; 
conectar ();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LA FOURNIER</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="./estilos/estilo_usuario.css" type="text/css">
</head>
<body>

    <!-- Encabezado -->
    <header>
    <h1>Bienvenidos a La Fournier</h1>
    </header>

    <!-- Barra de navegación -->
    <nav class="navbar">
        <div class="menu-icon" onclick="toggleMenu()">&#9776;</div>
        <ul class="menu">
            <li><a href="index.php">INICIO</a></li>
            <li><a href="index.php?modulo=nosotros.php">NOSOTROS</a></li>
            <li><a href="index.php?modulo=productos.php">PRODUCTOS</a></li>
            <li><a href="index.php?modulo=pedidos.php">PEDIDOS</a></li>
        </ul>
    </nav>

    <main>
        
        <?php
        if (!empty($_GET['modulo'])) {
            include('modulos/usuarios/' . $_GET['modulo'] );
        } else {
            ?>
            <section class="novedades">
                <h2>Novedades</h2>
                <?php
                // Consulta SQL para obtener las últimas novedades
                $sql = "SELECT encabezado, description, fecha_alta FROM novedades ORDER BY fecha_alta DESC LIMIT 3";
                $result = $con->query($sql);

                if ($result && $result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<div class='novedad'>";
                        echo "<h3>" . htmlspecialchars($row['encabezado']) . "</h3>";  // Título de la novedad
                        echo "<p class='description'>" . htmlspecialchars($row['description']) . "</p>";  // Descripción breve
                        echo "<small class='fecha'>Publicado el: " . date('d-m-Y', strtotime($row['fecha_alta'])) . "</small>";  // Fecha de publicación
                        echo "</div>";
                    }
                } else {
                    echo "<p>No hay novedades en este momento.</p>";
                }
                ?>
            </section>

            <!-- Sección de imágenes (con un diseño atractivo) -->
            <section class="news_copadas">
                <?php
                $id_imagen = isset($_GET['id_imagen']) ? intval($_GET['id_imagen']) : 0;

                if ($id_imagen > 0) {
                    $sql = mysqli_query($con, "SELECT imagen FROM imagenes_novedades WHERE id = $id_imagen");
                    if ($sql && $r = mysqli_fetch_array($sql)) {
                        header("Content-Type: image/jpeg"); 
                        echo $r['imagen']; 
                        exit; 
                    }
                }

                $sql_images = "SELECT imagen FROM imagenes_novedades ORDER BY fecha_alta DESC LIMIT 6";
                $result_images = $con->query($sql_images);

                if ($result_images && $result_images->num_rows > 0) {
                    while ($image_row = $result_images->fetch_assoc()) {
                        // Suponiendo que 'imagen' es una URL o ruta
                        echo "<img src='imagenes/novedades/" . htmlspecialchars($image_row['imagen']) . "' alt='Imagen de novedad'>";
                    }
                } else {
                    echo "<p>No hay imágenes disponibles.</p>";
                }

                // Cerrar la conexión
                $con->close();
                ?>
            </section>
            <?php
        }
    ?>

    </main>

    <footer>
        <p>&copy; 2024 La Fournier. Todos los derechos reservados.</p>
    </footer>

    <script src="./scripts/funciones.js"></script>
</body>
</html>
