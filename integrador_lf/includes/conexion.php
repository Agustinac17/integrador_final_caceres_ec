<?php
function conectar() {
    global $con;
    $con = mysqli_connect("localhost", "root", "root", "lafournier_bd");
    
    // Comprobar la conexión
    if (mysqli_connect_errno()) {
        printf("Falló la conexión: %s\n", mysqli_connect_error());
        exit();
    } else {
        $con->set_charset("utf8");
        $ret = true; // Inicializar la variable $ret
    }

    return $ret; // Retornar el estado de conexión
}

function desconectar() {
    global $con;
    if (isset($con)) { // Verificar si $con está definida
        mysqli_close($con);
    }
}

