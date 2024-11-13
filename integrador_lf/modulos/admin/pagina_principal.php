<?php
// Función para gestionar novedades
function agregarNovedad($encabezado, $description) {
    global $con;
    $stmt = $con->prepare("INSERT INTO novedades (encabezado, description, fecha_alta) VALUES (?, ?, NOW())");
    $stmt->bind_param("ss", $encabezado, $description);
    $stmt->execute();
    $stmt->close();
}

function modificarNovedad($id, $encabezado, $description) {
    global $con;
    $stmt = $con->prepare("UPDATE novedades SET encabezado = ?, description = ? WHERE id_novedad = ?");
    $stmt->bind_param("ssi", $encabezado, $description, $id);
    $stmt->execute();
    $stmt->close();
}

function eliminarNovedad($id) {
    global $con;
    $stmt = $con->prepare("DELETE FROM novedades WHERE id_novedad = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
}

// Función para manejar la carga de imágenes
function subirImagen($file) {
    $allowed_types = ['image/jpeg', 'image/png', 'image/gif']; // Tipos permitidos
    $max_size = 5 * 1024 * 1024; // Tamaño máximo de 5MB

    if (!in_array($file['type'], $allowed_types)) {
        echo "Solo se permiten imágenes en formato JPG, PNG o GIF.";
        return;
    }

    if ($file['size'] > $max_size) {
        echo "El archivo es demasiado grande. El tamaño máximo permitido es 5MB.";
        return;
    }

    //$target_dir = "../imagenes/novedades/";
    $target_file = basename($file["name"]);

    if (move_uploaded_file($file["tmp_name"], "../imagenes/novedades/".$target_file)) {
        global $con;
        $stmt = $con->prepare("INSERT INTO imagenes_novedades (imagen, fecha_alta) VALUES (?, NOW())");
        $stmt->bind_param("s", $target_file);
        $stmt->execute();
        $stmt->close();
    } else {
        echo "Error al subir la imagen.";
    }
}

function eliminarImagen($id, $imagen) {
    // Define la ruta de destino para el archivo
    $target_file = "../imagenes/novedades/" . basename($imagen);
    
    // Verifica si el archivo existe en la ruta especificada
    if (file_exists($target_file)) {
        // Intenta eliminar el archivo
        if (unlink($target_file)) { // Elimina el archivo del sistema
            global $con;
            // Prepara la consulta para eliminar la entrada en la base de datos
            $stmt = $con->prepare("DELETE FROM imagenes_novedades WHERE id_imagen = ?");
            $stmt->bind_param("s", $id);
            $stmt->execute();
            $stmt->close();
        } else {
            echo "Error al eliminar el archivo del sistema.";
        }
    } else {
        echo "El archivo no existe.";
    }
}


/********************************************/

// Manejo de las peticiones POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Agregar nueva novedad
    if (isset($_POST['agregar_novedad'])) {
        $encabezado = $_POST['encabezado'];
        $description = $_POST['description'];
        agregarNovedad($encabezado, $description);

    }

    // Modificar novedad
    if (isset($_POST['modificar_novedad'])) {
        $id = $_POST['id_novedad'];
        $encabezado = $_POST['encabezado'];
        $description = $_POST['description'];
        modificarNovedad($id, $encabezado, $description);

    }

    // Eliminar novedad
    if (isset($_POST['eliminar_novedad'])) {
        $id = $_POST['id_novedad'];
        eliminarNovedad($id);
    }

    // Subir imagen
    if (isset($_POST['subir_imagen'])) {
        //var_dump($_FILES["fileToUpload"]);
        subirImagen($_FILES["fileToUpload"]);
    }

    if (isset($_POST['eliminar_imagen'])) {
        //var_dump($_FILES["fileToUpload"]);
        eliminarImagen($_POST['id'],$_POST['imagen']);
    }

}
?>

<main>
    <section class="novedades">
        <h2>Novedades</h2>

        <!-- Formulario para agregar una novedad -->
        <form method="POST">
            <input type="text" name="encabezado" placeholder="Encabezado de la novedad" required>
            <textarea name="description" placeholder="Descripción de la novedad" required></textarea>
            <button type="submit" name="agregar_novedad">Agregar Novedad</button>
        </form>

        <h3>Modificar o Eliminar Novedades</h3>

        <?php
        // Consulta SQL para obtener las últimas novedades
        $sql = "SELECT * FROM novedades ORDER BY fecha_alta DESC";
        $result = $con->query($sql);

        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<div class='novedad'>";
                echo "<h3>" . htmlspecialchars($row['encabezado']) . "</h3>";  // Título de la novedad
                echo "<p class='description'>" . htmlspecialchars($row['description']) . "</p>";  // Descripción breve
                echo "<small class='fecha'>Publicado el: " . date('d-m-Y', strtotime($row['fecha_alta'])) . "</small>";  // Fecha de publicación
                echo "<form method='POST'>
                        <input type='hidden' name='id_novedad' value='" . $row['id_novedad'] . "'>
                        <button type='submit' name='eliminar_novedad'>Eliminar</button>
                        <input type='text' name='encabezado' value='" . $row['encabezado'] . "' required>
                        <textarea name='description' required>" . $row['description'] . "</textarea>
                        <button type='submit' name='modificar_novedad'>Modificar</button>
                      </form>";
                echo "</div>";
            }
        } else {
            echo "<p>No hay novedades en este momento.</p>";
        }
        ?>
    </section>

    <!-- Sección de imágenes -->
    <section class="news_copadas">
        <h3>Subir Imagen</h3>
        <form method="POST" enctype="multipart/form-data">
            <input type="file" name="fileToUpload" required>
            <button type="submit" name="subir_imagen">Subir Imagen</button>
        </form>

        <h3>Imágenes asociadas a novedades</h3>
        <?php
        // Consulta SQL para obtener las imágenes asociadas a las novedades
        $sql_images = "SELECT imagen, id_imagen FROM imagenes_novedades ORDER BY fecha_alta DESC LIMIT 6";
        $result_images = $con->query($sql_images);

        if ($result_images && $result_images->num_rows > 0) {
            while ($image_row = $result_images->fetch_assoc()) {
                echo "<img src='../imagenes/novedades/" . htmlspecialchars($image_row['imagen']) . "' alt='Imagen de novedad'>";
                echo '<form method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="imagen" value="'.$image_row['imagen'].'" required>
                    <input type="hidden" name="id" value="'.$image_row['id_imagen'].'"required>
               <button type="submit" name="eliminar_imagen">Eliminar Imagen</button>
                </form>';
            }
        } else {
            echo "<p>No hay imágenes disponibles.</p>";
        }
        ?>
    </section>
</main>

<?php
// Cerrar la conexión
$con->close();
?>
