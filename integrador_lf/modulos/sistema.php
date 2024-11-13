<?php include '../includes/conexion.php'; 
conectar ();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../estilos/estilo_admin.css" type="text/css">
</head>
<body>
    <!-- Barra lateral de navegación -->
    <div class="sidebar">
        <h2>Admin Panel</h2>
        <a href="sistema.php" id="inicio">INICIO</a>
        <a href="sistema.php?modulo=pagina_principal" id="pagina_principal">Pagina Principal</a>
        <a href="sistema.php?modulo=config_productos" id="config_productos">Productos</a>
        <a href="sistema.php?modulo=config_pedidos" id="config_pedidos">Pedidos</a>
        <a href="sistema.php?modulo=config_nosotros" id="config_nosotros">Nosotros</a>
    </div>

    <?php
        if(isset($_GET['modulo'])){
            // Ensure safe inclusion of the requested module
            $allowed_modules = ['pagina_principal', 'config_productos', 'config_pedidos', 'config_nosotros', 'config_recuerdo'];
            if (in_array($_GET['modulo'], $allowed_modules)) {
                include('admin/' . $_GET['modulo'] . '.php');
            } else {
                echo 'Invalid module.';
            }
        } else {
    ?>
        <!-- Contenido principal -->
        <div class="content">
            <h1>Dashboard de Administración</h1>

            <!-- Sección de Eventos -->
            <div class="section" id="pagina_principal">
                <h2>Configuración de la pagina principal</h2>
                <p>Visualiza y modifica el contenido de las novedades</p>
            </div>

            <!-- Sección de Pedidos -->
            <div class="section" id="config_productos">
                <h2>Productos</h2>
                <p>Vizualizar, agregar, eliminar o editar productos y sus precios</p>
            </div>

            <!-- Sección de Contenido -->
            <div class="section" id="config_pedidos_eventos">
                <h2>Pedidos</h2>
                <p>Vizualiza pedidos pendientes</p>
            </div>

            <!-- Sección de Imágenes -->
            <div class="section" id="config_nosotros">
                <h2>Nosotros</h2>
                <p>Gestiona las imágenes que aparecen la sección Nosotros</p>
            </div>

        </div>
        <?php
    }
?>
 
</body>
</html>
