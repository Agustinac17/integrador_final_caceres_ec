<?php
// Función para agregar o actualizar el texto de la sección "Nosotros"
function actualizarTextoNosotros($texto) {
    global $con;
    $stmt = $con->prepare("INSERT INTO nosotros (texto) VALUES (?) ON DUPLICATE KEY UPDATE texto = VALUES(texto)");
    $stmt->bind_param("s", $texto);
    $stmt->execute();
    $stmt->close();
}

// Función para subir una imagen a "imagenes_nosotros"
function subirImagenNosotros($file) {
    $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
    $max_size = 5 * 1024 * 1024;

    if (!in_array($file['type'], $allowed_types)) {
        echo "Solo se permiten imágenes en formato JPG, PNG o GIF.";
        return;
    }

    if ($file['size'] > $max_size) {
        echo "El archivo es demasiado grande. El tamaño máximo permitido es 5MB.";
        return;
    }

    $target_file = basename($file["name"]);
    if (move_uploaded_file($file["tmp_name"], "../imagenes/nosotros/" . $target_file)) {
        global $con;
        $stmt = $con->prepare("INSERT INTO imagenes_nosotros (imagen) VALUES (?)");
        $stmt->bind_param("s", $target_file);
        $stmt->execute();
        $stmt->close();
    } else {
        echo "Error al subir la imagen.";
    }
}

// Función para eliminar una imagen
function eliminarImagenNosotros($id, $imagen) {
    $target_file = "../imagenes/nosotros/" . basename($imagen);
    if (file_exists($target_file)) {
        if (unlink($target_file)) {
            global $con;
            $stmt = $con->prepare("DELETE FROM imagenes_nosotros WHERE id_img_nos = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $stmt->close();
        } else {
            echo "Error al eliminar el archivo.";
        }
    } else {
        echo "El archivo no existe.";
    }
}

// Manejo de las peticiones POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['actualizar_texto'])) {
        $texto = $_POST['texto'];
        actualizarTextoNosotros($texto);
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit;
    }

    if (isset($_POST['subir_imagen'])) {
        subirImagenNosotros($_FILES["fileToUpload"]);
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit;
    }

    if (isset($_POST['eliminar_imagen'])) {
        eliminarImagenNosotros($_POST['id'], $_POST['imagen']);
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit;
    }
}
?>

<!-- Formulario para gestionar el contenido de "Nosotros" -->
<div class="content">
    <form method="POST">
        <textarea name="texto" placeholder="Escribe la historia de los dueños" required></textarea>
        <button type="submit" name="actualizar_texto">Actualizar Texto</button>
        <br>
    </form>

    <!-- Formulario para subir imágenes -->
    <form method="POST" enctype="multipart/form-data">
        <input type="file" name="fileToUpload" required>
        <button type="submit" name="subir_imagen">Subir Imagen</button>
        <br>
    </form>

    <!-- Lista de imágenes actuales con opción de eliminar -->
    <?php
    $sql_images = "SELECT id_img_nos, imagen FROM imagenes_nosotros";
    $result_images = $con->query($sql_images);

    if ($result_images && $result_images->num_rows > 0) {
        while ($image_row = $result_images->fetch_assoc()) {
            echo "<img src='../imagenes/nosotros/" . htmlspecialchars($image_row['imagen']) . "' alt='Imagen de la familia' class='imagen-pequena'>";
            echo '<form method="POST">
                    <input type="hidden" name="imagen" value="'.$image_row['imagen'].'">
                    <input type="hidden" name="id" value="'.$image_row['id_img_nos'].'">
                    <button type="submit" name="eliminar_imagen">Eliminar Imagen</button>
                </form>';
        }
    } else {
        echo "<p>No hay imágenes disponibles.</p>";
    }
    ?>

</div>
