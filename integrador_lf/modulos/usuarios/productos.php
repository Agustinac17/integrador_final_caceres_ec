<style>
    /* Contenedor principal de la sección de productos */
    .productos_box {
        width: 100%;
        margin: auto;
        text-align: center;
        padding: 20px;
        background-color: #d4b879cc;
        border-radius: 10px;
        box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
    }

    /* Título principal */
    .productos_box h2 {
        font-size: 24px;
        color: #333;
        margin-bottom: 20px;
        font-family: Arial, sans-serif;
    }

    /* Contenedor de productos con Flexbox */
    #productos_container {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        gap: 20px;
    }

    /* Estilo individual de cada producto */
    .producto {
        width: 200px;
        background-color: #ffffff;
        border: 1px solid #ddd;
        border-radius: 8px;
        overflow: hidden;
        transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
    }

    /* Efecto hover para productos */
    .producto:hover {
        transform: scale(1.05);
        box-shadow: 0px 8px 16px rgba(0, 0, 0, 0.2);
    }

    /* Imagen del producto */
    .producto img {
        width: 100%;
        height: auto;
        border-bottom: 1px solid #ddd;
    }

    /* Nombre del producto */
    .producto h2 {
        font-size: 18px;
        color: #555;
        margin: 10px 0;
        font-family: 'Arial', sans-serif;
    }

    /* Precio del producto */
    .producto p {
        font-size: 16px;
        color:coral;
        margin: 0 0 15px;
        font-weight: bold;
    }

    /* Diseño responsivo */
    @media (max-width: 600px) {
        .producto {
            width: 100%;
        }
    }
</style>


<!-- Sección de productos en forma de box -->
<section class="productos_box">
    <h2>Elegí lo que más te guste</h2>
    <div id="productos_container"> <!-- Aquí se cargarán los productos con JS -->
        <!-- Los productos serán insertados aquí por JavaScript -->
    </div>
</section>

<!-- Agregar código JavaScript directamente en el archivo -->
<script>

const basePath = 'imagenes/productos/'; // Ruta relativa a index.php

    // Función para obtener productos usando AJAX
    function obtenerProductos() {
        fetch('includes/obtener_productos.php') // Hace la solicitud al archivo PHP
            .then(response => response.json()) // Convierte la respuesta a JSON
            .then(data => {
                console.log(data); // Verifica los datos que estás recibiendo
                const productosContainer = document.getElementById('productos_container');
                productosContainer.innerHTML = ''; // Limpiar contenedor

                if (data.length > 0) {
                    data.forEach(producto => {
                        const precio = parseFloat(producto.precio);
                        const imagenPath = `../imagenes/productos/${producto.imagen_path}`;
                        console.log("Ruta de la imagen:", imagenPath); // Verifica la ruta que se está generando

                        const productoDiv = document.createElement('div');
                        productoDiv.classList.add('producto');
                        productoDiv.innerHTML = `
                            <img src="/integrador_lf/imagenes/productos/${producto.imagen_path}" alt="${producto.nombre}">
                            <h2>${producto.nombre}</h2>
                            <p>Precio: $${precio.toFixed(2)}</p>
                        `;

                        productosContainer.appendChild(productoDiv);
                    });
                } else {
                    productosContainer.innerHTML = '<p>No hay productos disponibles.</p>';
                }
            })

            .catch(error => console.error('Error al cargar los productos:', error));
    }

    // Llamar a la función para obtener los productos cuando la página se cargue
    window.onload = obtenerProductos;
</script>
