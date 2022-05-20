<?php
//require_once '../vendor/autoload.php'; //libreria de composer
use Firebase\JWT\JWT; //libreria de jwt
/**
 * Función que retorna el usuario actual con inicio de sesión con token
 */
function usuarioActual()
{
    $jwt = $_SERVER['HTTP_AUTHORIZATION'];
    $key = 'my_secret_key';
    if (substr($jwt, 0, 6) === "Bearer") {
        $jwt = str_replace("Bearer ", "", $jwt);
        
        try {
            $data = JWT::decode($jwt,$key, array('HS256'));
            $datos = $data->data;
            return $datos->usuario;
        } catch (\Throwable $th) {
            echo 'error: ';
            return '';
        }
    } 
        return '';
    
}

/**
 * Función que limpia la llave valor del metodo _POST
 */
function LimpiezaKV()
{
    foreach ($_POST as $key => $value) {
        $_POST[$key] = Limpieza($value);
    }
}
/**
 * Función que limpia todos los datos de entrada
 * @param cadena: Recibe la cadena a limpiar.
 */
// Limpieza de datos de entrada
function Limpieza($cadena)
{
    $patron = array('/<script>.*<\/script>/');
    $cadena = preg_replace($patron, '', $cadena);
    $cadena = htmlspecialchars($cadena);
    return $cadena;
}

/**
 * Función para limpiar parametros de entrada 
 * 
 * */
function limpiarEntradas()
{
    if (isset($_POST)) {
        foreach ($_POST as $key => $value) {
            $_POST[$key] = Limpieza($value);
        }
    }
}
/**
 * Función que permite el aseguramiento de la sesión
 * 
 */
function sesionSegura()
{
    //obtener los parametros de la cookie de sesión
    $cookieParams = session_get_cookie_params();
    $path = $cookieParams["path"];

    //inicio y control de la sesion
    $secure = true;
    $httpOnly = true;
    $sameSite = 'strict';

    session_set_cookie_params([
        'lifetime' => $cookieParams["lifetime"],
        'path'    => $path,
        'domain'  => $_SERVER['HTTP_HOST'],
        'secure'  => $secure,
        'httponly' => $httpOnly,
        'samesite' => $sameSite
    ]);
    session_start();
    session_regenerate_id(true); //permite que cada llamado se genere una nueva sesión

}
/**
 * Función que cierra la sesión del usuario
 */
function cerrarSesion()
{
    session_destroy();
    header("refresh:3;url=index.php");
}

/**
 * funcion que genera un número aleatorio cada vez que haga un envío de datos.
 */
function anticsrf()
{
    $anticsrf = random_int(1000000, 9999999);
    $_SESSION['anticsrf'] = $anticsrf;
}

/**
 * Función que recibe una cadena y retorna true si es texto
 * @param texto: Recibe la cadena y valida si cumple con el patron de texto 
 */
function validarTexto($texto)
{
    $tex = trim($texto);
    if ($tex == "" && trim($tex) == "") {
        return false;
    } else {
        $patron = '/^[a-zA-Z, ]*$/';
        if (preg_match($patron, $tex)) {
            return true;
        } else {
            return false;
        }
    }
}
/**
 * Función que recibe una cadena y retorna true si es texto válido y menor de 140 caracteres
 * @param texto: Recibe la cadena y valida si cumple con el patron de texto 
 */
function validarArticulo($texto)
{
    if (strlen($texto) <= 140) {
        $tex = trim($texto);
        if ($tex == "" && trim($tex) == "") {
            return false;
        } else {
            $patron = '/^[a-zA-Z0-9!¡¿?.,*áéíóúÁÉÍÓÚñÑ, ]*$/';
            if (preg_match($patron, $tex)) {
                return true;
            } else {
                return false;
            }
        }
    } else {
        return false;
    }
}
/**
 * Función que recibe una cadena y retorna true si es un mensaje válido
 * @param texto: Recibe la cadena y valida si cumple con el patron de texto 
 */
function validarMensaje($texto)
{
    if (strlen($texto) <= 140) {
        $tex = trim($texto);
        if ($tex == "" && trim($tex) == "") {
            return false;
        } else {
            $patron = '/^[a-zA-Z0-9!¡¿?.,*áéíóúÁÉÍÓÚñÑ, ]*$/';
            if (preg_match($patron, $tex)) {
                return true;
            } else {
                return false;
            }
        }
    } else {
        return false;
    }
}
/**
 * Función que recibe una cadena y retorna true si es una fecha
 * @param fecha: Recibe la cadena y valida si cumple con el patron de fecha en el formato solicitado 
 */
function validarFecha($fecha)
{
    $fe = explode('-', $fecha);
    if (count($fe) != 3) {
        return false;
    } else if (checkdate($fe[1], $fe[2], $fe[0]) == true) {
        return true;
    } else {
        return false;
    }
}

/**
 * Función que recibe una cadena y retorna true si es un correo
 * @param correo: Recibe la cadena y valida si cumple con el patron de correo 
 */
function validarCorreo($correo)
{
    if (filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        return true;
    } else {
        return false;
    }
}

/**
 * Función que recibe una cadena y retorna true si es un color
 * @param color: Recibe la cadena y valida si cumple con el patron de color 
 */
function validarColor($color)
{
    if (preg_match("((#)[0-9a-fA-F]{6})", $color)) {
        return true;
    } else {
        return false;
    }
}

/**
 * Función que recibe una cadena y retorna true si es un usuario
 * @param user: Recibe la cadena y valida si cumple con el patron de usuario 
 */
function validarUsuario($user)
{
    $us = trim($user);
    if ($us == "" && trim($us) == "") {
        return false;
    } else {
        $patron = '/^[a-zA-Z0-9, ]*$/';
        if (preg_match($patron, $us)) {
            return true;
        } else {
            return false;
        }
    }
}
/**
 * Función que recibe una cadena y retorna true si es un documento válido.
 * @param doc: Recibe la cadena y valida si cumple con el patron del número de documento.
 */
function validarDocumento($doc)
{
    $us = trim($doc);
    if ($us == "" && trim($us) == "") {
        return false;
    } else {
        $patron = '/^[a-zA-Z0-9, ]*$/';
        if (preg_match($patron, $us)) {
            return true;
        } else {
            return false;
        }
    }
}
/**
 * Función que recibe una cadena y retorna true si es una clave
 * @param user: Recibe la cadena y valida si cumple con el patron de clave
 */
function validarClave($clave)
{
    if (strlen($clave) < 6) {
        echo 'La clave debe tener al menos 6 caracteres';
        return false;
    }
    if (strlen($clave) > 16) {
        echo 'La clave no puede tener más de 16 caracteres';
        return false;
    }
    if (!preg_match('`[a-z]`', $clave)) {
        return false;
    }
    if (!preg_match('`[A-Z]`', $clave)) {
        return false;
    }
    if (!preg_match('`[0-9]`', $clave)) {
        return false;
    }
    if (!preg_match('`[*,+,/,#]`', $clave)) {        
        return false;
    }
    return true;
}
/**
 * Función que recibe una cadena y retorna true si es una direccion
 * @param clave: Recibe la cadena y valida si cumple con el patron de la dirección
 */
function validarDireccion($direccion)
{
    $cla = trim($direccion);
    if ($cla == "" && trim($cla) == "") {
        return false;
    } else {
        $patron = '/^[a-zA-Z0-9 # - ]*$/';
        if (preg_match($patron, $cla)) {
            return true;
        } else {
            return false;
        }
    }
}
/**
 * Función que recibe una cadena y retorna true si es una direccion
 * @param tuit: Recibe la cadena y valida si cumple con el patron del tuit
 */
function validarTuit($tuit)
{
    if (strlen($tuit) <= 140) {
        return true;
    } else {
        return false;
    }
}
/**
 * Función que muestra las echo al usuario
 * @param notificacion: Mensaje para mostrar.
 */
function notificaciones($notificacion)
{

?>
    <div><label name="notificacion"> <?php echo $notificacion ?></label></div>
<?php

}
/**
 * Función para decodificar una imagen enviada desde el servidor
 * @param: base64_string: Cadena en base 64 del archivo
 * @param: output_file: Ruta donde se aloja la imagen
 */
function base64_to_jpeg($base64_string, $output_file) {
    // open the output file for writing
    $ifp = fopen( $output_file, 'wb' ); 

    // split the string on commas
    // $data[ 0 ] == "data:image/png;base64"
    // $data[ 1 ] == <actual base64 string>
    $data = explode( ',', $base64_string );

    // we could add validation here with ensuring count( $data ) > 1
    fwrite( $ifp, base64_decode( $data[ 1 ] ) );

    // clean up the file resource
    fclose( $ifp ); 
    return $output_file; 
}

?>