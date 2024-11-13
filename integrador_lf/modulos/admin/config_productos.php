<?php
// Función para agregar un producto
function agregarProducto($nombre, $descripcion, $precio, $imagen_path, $id_categoria, $stock) {
    global $con;
    $stmt = $con->prepare("INSERT INTO producto (nombre, descripcion, precio, imagen_path, id_categoria, stock, fecha_alta) VALUES (?, ?, ?, ?, ?, ?, NOW())");
    $stmt->bind_param("ssdsii", $nombre, $descripcion, $precio, $imagen_path, $id_categoria, $stock);
    $stmt->execute();
    $stmt->close();
}

// Función para modificar un producto existente
function modificarProducto($id_producto, $nombre, $descripcion, $precio, $imagen_path, $id_categoria, $stock) {
    global $con;
    $stmt = $con->prepare("UPDATE producto SET nombre = ?, descripcion = ?, precio = ?, imagen_path = ?, id_categoria = ?, stock = ? WHERE id_producto = ?");
    $stmt->bind_param("ssdsiii", $nombre, $descripcion, $precio, $imagen_path, $id_categoria, $stock, $id_producto);
    $stmt->execute();
    $stmt->close();
}

// Función para eliminar un producto
function eliminarProducto($id_producto) {
    global $con;
    $stmt = $con->prepare("DELETE FROM producto WHERE id_producto = ?");
    $stmt->bind_param("i", $id_producto);
    $stmt->execute();
    $stmt->close();
}

// Función para listar productos
function listarProductos() {
    global $con;
    $sql = "SELECT * FROM producto ORDER BY fecha_alta DESC";
    return $con->query($sql);
}
?>

<?php
// Función para agregar una categoría
function agregarCategoria($id, $nombre_categoria) {
    global $con;
    // Inserta ambos valores en la consulta
    $stmt = $con->prepare("INSERT INTO categorias (id_categoria, nombre_categoria) VALUES (?, ?)");
    $stmt->bind_param("ss", $id, $nombre_categoria);
    $stmt->execute();                                       
    $stmt->close();
}

// Función para listar categorías (útil para seleccionar al agregar un producto)
function listarCategorias() {
    global $con;
    $sql = "SELECT * FROM categorias ORDER BY nombre_categoria";
    return $con->query($sql);
}
?>

<?php
function subirImagenProducto($file) {
    $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
    $max_size = 5 * 1024 * 1024;

    if (!in_array($file['type'], $allowed_types)) {
        echo "Formato de imagen no permitido. Solo JPG, PNG o GIF.";
        return false;
    }

    if ($file['size'] > $max_size) {
        echo "El tamaño de la imagen excede el límite permitido de 5MB.";
        return false;
    }

    $target_dir = "../imagenes/productos/";
    $target_file = $target_dir . basename($file["name"]);

    if (move_uploaded_file($file["tmp_name"], $target_file)) {
        return $target_file;
    } else {
        echo "Error al subir la imagen.";
        return false;
    }
}
?>

<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Agregar un nuevo producto
    if (isset($_POST['agregar_producto'])) {
        $nombre = $_POST['nombre'];
        $descripcion = $_POST['descripcion'];
        $precio = $_POST['precio'];
        $id_categoria = $_POST['id_categoria'];
        $stock = $_POST['stock'];
        $imagen_path = subirImagenProducto($_FILES['imagen']);

        if ($imagen_path) {
            agregarProducto($nombre, $descripcion, $precio, $imagen_path, $id_categoria, $stock);
            header('Location: ' . $_SERVER['PHP_SELF']);
            exit;
        }
    }

    // Modificar un producto existente
    if (isset($_POST['modificar_producto'])) {
        $id_producto = $_POST['id_producto'];
        $nombre = $_POST['nombre'];
        $descripcion = $_POST['descripcion'];
        $precio = $_POST['precio'];
        $id_categoria = $_POST['id_categoria'];
        $stock = $_POST['stock'];
        $imagen_path = subirImagenProducto($_FILES['imagen']);

        if ($imagen_path) {
            modificarProducto($id_producto, $nombre, $descripcion, $precio, $imagen_path, $id_categoria, $stock);
            header('Location: ' . $_SERVER['PHP_SELF']);
            exit;
        }
    }

    // Eliminar un producto
    if (isset($_POST['eliminar_producto'])) {
        $id_producto = $_POST['id_producto'];
        eliminarProducto($id_producto);
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit;
    }

    // Verifica si se ha enviado el formulario para agregar una categoría
    if (isset($_POST['agregar_categoria'])) {
        agregarCategoria($_POST['id'], $_POST['nombre_categoria']);
        // Puedes descomentar las siguientes líneas para redirigir después de la inserción:
        //header('Location: ' . $_SERVER['PHP_SELF']);
        //exit;
    }
}
?>

<div class="content">
    <h1>Gestión de Productos</h1>

    <!-- Formulario para agregar un producto -->
    <h2>Agregar Producto</h2>
    <form method="POST" enctype="multipart/form-data">
        <input type="text" name="nombre" placeholder="Nombre del producto" required><br>
        <textarea name="descripcion" placeholder="Descripción del producto" required></textarea><br>
        <input type="number" step="0.01" name="precio" placeholder="Precio" required><br>
        
        <!-- Selección de categoría -->
        <select name="id_categoria" required>
            <option value="">Seleccionar categoría</option>
            <?php
            $categorias = listarCategorias();
            while ($categoria = $categorias->fetch_assoc()) {
                echo "<option value='" . $categoria['id_categoria'] . "'>" . htmlspecialchars($categoria['nombre_categoria']) . "</option>";
            }
            ?>
        </select><br>

        <input type="number" name="stock" placeholder="Stock" required><br>
        <input type="file" name="imagen" required><br>
        <button type="submit" name="agregar_producto">Agregar Producto</button>
    </form>

    <!-- Listado de productos -->
    <h2>Lista de Productos</h2>
    <?php
    $productos = listarProductos();
    if ($productos && $productos->num_rows > 0) {
        while ($producto = $productos->fetch_assoc()) {
            echo "<div>";
            echo "<p><strong>" . htmlspecialchars($producto['nombre']) . "</strong></p>";
            echo "<p>" . htmlspecialchars($producto['descripcion']) . "</p>";
            echo "<p>Precio: $" . number_format($producto['precio'], 2) . "</p>";
            echo "<p>Stock: " . $producto['stock'] . "</p>";
            echo "<img src='" . htmlspecialchars($producto['imagen_path']) . "' alt='Imagen del producto' width='100'><br>";

            // Formulario para modificar el producto
            echo "<form method='POST' enctype='multipart/form-data'>";
            echo "<input type='hidden' name='id_producto' value='" . $producto['id_producto'] . "'>";
            echo "<input type='text' name='nombre' value='" . htmlspecialchars($producto['nombre']) . "' required><br>";
            echo "<textarea name='descripcion' required>" . htmlspecialchars($producto['descripcion']) . "</textarea><br>";
            echo "<input type='number' step='0.01' name='precio' value='" . $producto['precio'] . "' required><br>";
            echo "<select name='id_categoria' required>";
            
            // Selección de categoría
            $categorias = listarCategorias();
            while ($categoria = $categorias->fetch_assoc()) {
                $selected = $categoria['id_categoria'] == $producto['id_categoria'] ? "selected" : "";
                echo "<option value='" . $categoria['id_categoria'] . "' $selected>" . htmlspecialchars($categoria['nombre_categoria']) . "</option>";
            }
            
            echo "</select><br>";
            echo "<input type='number' name='stock' value='" . $producto['stock'] . "' required><br>";
            echo "<input type='file' name='imagen'><br>";
            echo "<button type='submit' name='modificar_producto'>Modificar Producto</button>";
            echo "</form>";

            // Formulario para eliminar el producto
            echo "<form method='POST'>";
            echo "<input type='hidden' name='id_producto' value='" . $producto['id_producto'] . "'>";
            echo "<button type='submit' name='eliminar_producto'>Eliminar Producto</button>";
            echo "</form>";

            echo "</div><hr>";
        }
    } else {
        echo "<p>No hay productos disponibles.</p>";
    }
    ?>

    <!-- Formulario para agregar una categoría -->
    <h2>Agregar Categoría</h2>
    <form method="POST">
        <input type="text" name="nombre_categoria" placeholder="Nombre de la categoría" required><br>
        <button type="submit" name="agregar_categoria">Agregar Categoría</button>
    </form>
</div>
