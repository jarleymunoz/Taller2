<?php

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
    $secure = false;
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
 * Función que devuelve el color escogido para mostrar al usuario registrado
 * * @param usuario: Recibe el nombre de usuario.
 * * @param pass:    Recibe la contraseña del usuario.
 */
/**
 * funcion que genera un número aleatorio cada vez que haga un envío de datos.
 */
function anticsrf()
{
    $anticsrf = random_int(1000000, 9999999);
    $_SESSION['anticsrf'] = $anticsrf;
}

/**
 * Función que guarda los datos de un usuario.
 * @param nombre:     Recibe el nombre de usuario.
 * @param apellido:   Recibe apellio del usuario.
 * @param fechaNac:   Recibe la fecha de nacimiento.
 * @param tipoDoc:    Recibe el tipo de documento.  
 * @param numDoc:     Recibe el número de documento.
 * @param img:        Recibe la ruta de la imagen.
 * @param numHij:     Recibe el número de hijos.
 * @param colorPref:  Recibe el color preferido.
 * @param user:       Recibe el nombre de usuario.
 * @param pass:       Recibe la contraseña del usuario.
 * 
 */
function grabarUsuario($user, $pass, $name, $lastName, $DateNac, $tipDoc, $numDoc, $img, $numHi, $color)
{
    //w  crea o sobreescribe
    //r  lectura del archivo
    //a  agrega al contenido existente
    $file = "usuarios.txt";
    $fp = fopen($file, "a");
    $txt = $user . ":" . $pass . ":" . $name . ":" . $lastName . ":" . $DateNac . ":" . $tipDoc . ":" . $numDoc . ":" . $img . ":" . $numHi . ":" . $color . "\n";
    fwrite($fp, $txt);
    fclose($fp);
}

/**
 * Función que actualiza los datos un usuario.
 * @param user:     Recibe el usuario.
 * @param pass:     Recibe la contraseña para validar usuario.
 * @param name:     Recibe el nuevo nombre del usuario.
 * @param lastName: Recibe el nuevo apellido del usuario.  
 * @param DateNac:  Recibe la nueva fecha de nacimiento.
 * @param tipDoc:   Recibe el nuevo tipo de documento.
 * @param numDoc:   Recibe el nuevo numero de documento.
 * @param img:      Recibe la ruta de la imagen.
 * @param numHi:    Recibe el nuevo numero de hijos.
 * @param color:    Recibe el nuevo color.
 * 
 */
function actualizarUsuario($user, $pass, $name, $lastName, $DateNac, $tipDoc, $numDoc, $img, $numHi, $color)
{
    $archivo = "usuarios.txt";
    $abrir = fopen($archivo, 'r+');
    $contenido = fread($abrir, filesize($archivo));
    fclose($abrir);
    $contenido2 = explode("\n", $contenido);
    for ($i = 0; $i < sizeof($contenido2); $i++) {
        //separamos la primera variable de la fila 3
        $variable2 = explode(":", $contenido2[$i]); //Esta es la posición de la fila
        if ($variable2[0] == $user && $variable2[1] == md5($pass)) {
            $variable2[2] = $name; //Esta es la posición de la columna
            $variable2[3] = $lastName; //Esta es la posición de la columna
            $variable2[4] = $DateNac; //Esta es la posición de la columna
            $variable2[5] = $tipDoc; //Esta es la posición de la columna
            $variable2[6] = $numDoc; //Esta es la posición de la columna
            $variable2[7] = $img;
            $variable2[8] = $numHi; //Esta es la posición de la columna
            $variable2[9] = $color; //Esta es la posición de la columna
            $variable2 = implode(":", $variable2);
            //devolvemos el valor a la linea    
            $contenido2[$i] = "$variable2";  //toda la fila 3 cambiada //Nuevamente la posición de la fila
        }
    }
    //juntamos lo demas
    $nuevo = implode("\n", $contenido2);
    $abrir = fopen($archivo, 'w');
    fwrite($abrir, $nuevo);
    fclose($abrir);
}

/**
 * Función que valida el usuario y contraseña de un archivo para ingresar.
 * guarda los datos del usuario para mostrarlos.
 * * @param usuario: Recibe el nombre de usuario.
 * * @param pass:    Recibe la contraseña del usuario.
 */
function leer($usuario, $pass)
{
    $file = "usuarios.txt";
    $fp = fopen($file, "r");
    while (!feof($fp)) {
        //$data=fread($fp,filesize($file));
        $data = fgets($fp);
        $salto = nl2br($data);
        $datos = explode(":", $salto);
        if ($usuario == $datos[0] && md5($pass) == $datos[1]) {
            $_SESSION["name"] = $datos[2];
            $_SESSION["lastname"] = $datos[3];
            $_SESSION["date"] = $datos[4];
            $_SESSION["doctype"] = $datos[5];
            $_SESSION["docnum"] = $datos[6];
            $_SESSION["img"] = $datos[7];
            $_SESSION["childnum"] = $datos[8];
            $_SESSION["favcol"] = $datos[9];

            return true;
        }
    }
    fclose($fp);
}
/**
 * Esta función modifica en el archivo usuarios la nueva clave agregada al usuario activo.
 * @param user:    Recibe el usuario.
 * @param passOld: Recibe la contraseña antigua.
 * @param passNew: Recibe la contraseña nueva.
 */
function cambiarClave($user, $passOld, $passNew)
{
    $archivo = "usuarios.txt";
    $abrir = fopen($archivo, 'r+');
    $contenido = fread($abrir, filesize($archivo));
    fclose($abrir);
    $contenido2 = explode("\n", $contenido);
    for ($i = 0; $i < sizeof($contenido2); $i++) {
        //separamos la primera variable de la fila 3
        $variable2 = explode(":", $contenido2[$i]); //Esta es la posición de la fila
        if ($variable2[0] == $user && $variable2[1] == md5($passOld)) {
            $variable2[1] = md5($passNew); //Esta es la posición de la columna
            $variable2 = implode(":", $variable2);
            //devolvemos el valor a la linea    
            $contenido2[$i] = "$variable2";  //toda la fila 3 cambiada //Nuevamente la posición de la fila
            //juntamos lo demas
        }
    }
    $nuevo = implode("\n", $contenido2);
    $abrir = fopen($archivo, 'w');
    fwrite($abrir, $nuevo);
    fclose($abrir);
}
/**
 * Función que lee todos los tuits que se encuentren en el archivo tuits
 */
function tuits()
{
    $file = "tuits.txt";
    $fp = fopen($file, "r");
    while (!feof($fp)) {
        //$data=fread($fp,filesize($file));
        $data = fgets($fp);
        $salto = nl2br($data);
        $datos = explode(":", $data);
        @$aux = $datos[0] . " " . $datos[1] . "\n" . "\n";
        echo $aux;
    }

    fclose($fp);
}
function limpiartuits()
{
    $file = "tuits.txt";
    $fp = fopen($file, "r");
    while (!feof($fp)) {
        $data = fgets($fp);
        if (!empty($data)) {
            str_replace(" ", "", $data);
        }
    }

    fclose($fp);
}
/**
 * Funcion que muestra los tuits de un usuario y da la opción de eliminarlos.
 */
function myTuits()
{
?>
    <?php
    if (isset($_POST["deleteTuit"])) {
        if (isset($_POST['anticsrf']) && isset($_SESSION['anticsrf']) && $_SESSION['anticsrf'] == $_POST['anticsrf']) {
            $line = (int)$_POST["deleteTuit"][0];
            deleteTuit($line);
            header("location: myTuits.php");
        } else {
            echo '<script language="javascript">alert("Petición inválida");</script>';
        }
    }
    anticsrf();
    ?>
    <html>
    <form method="POST">
        <?php
        $file = "tuits.txt";
        $fp = fopen($file, "r");
        while (!feof($fp)) {
            $data = fgets($fp);
            $salto = nl2br($data);
            $datos = explode(":", $data);
            if ($_SESSION["user"] == $datos[0]) {
                @$aux = $datos[0] . " " . $datos[1] . " ";
                echo $aux;
        ?>
                <input type="hidden" id="anticsrf" name="anticsrf" value="<?php echo $_SESSION['anticsrf'] ?>">
                <input style="background:#f11;" class="btnDelete" type="submit" name="deleteTuit" value="<?php $linea = $datos[2];
                                                                                                            echo $linea . "Delete"; ?>"><br><br>
    </form>

    </html>
<?php
            }
        }

        fclose($fp);
    }
    /**
     * Función que elimina la linea escogida del archivo de tuits.
     * @param linea: Recibe la linea del archivo de tuits a eliminar.
     */
    function deleteTuit($linea)
    {
        $archivo = "tuits.txt";
        $abrir = fopen($archivo, 'r+');
        $contenido = fread($abrir, filesize($archivo));
        fclose($abrir);
        $contenido2 = explode("\n", $contenido);
        for ($i = 0; $i < sizeof($contenido2); $i++) {
            //separamos la primera variable de la fila 3
            $variable2 = explode(":", $contenido2[$i]); //Esta es la posición de la fila
            if ($linea == $variable2[2]) {
                $variable2 = ""; //Esta es la posición de la columna
                $contenido2[$i] = "$variable2";  //toda la fila 3 cambiada //Nuevamente la posición de la fila
                //juntamos lo demas
            }
        }
        $nuevo = implode("\n", $contenido2);
        $abrir = fopen($archivo, 'w');
        fwrite($abrir, $nuevo);
        fclose($abrir);
    }

    /**
     * Función que guarda los tuits publicados por cada usuario.
     * @param user: Recibe el nombre de usuario que agrega el tuit.
     * @param tuit: Recibe el texto del tuit.  
     */
    function grabarTuit($user, $tuit)
    {
        //w  crea o sobreescribe
        //r  lectura del archivo
        //a  agrega al contenido existente
        $archivo = fopen("tuits.txt", "r");
        $num_lineas = 1;
        while (!feof($archivo)) {
            //si extraigo una línea del archivo y no es false
            if ($linea = fgets($archivo)) {
                //acumulo una en la variable número de líneas
                $num_lineas++;
            }
        }
        fclose($archivo);

        $file = "tuits.txt";
        $fp = fopen($file, "a");
        $txt = $user . ":" . $tuit . ":" . $num_lineas . "\n";
        fwrite($fp, $txt);
        fclose($fp);
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
        $cla = trim($clave);
        if ($cla == "" && trim($cla) == "") {
            return false;
        } else {
            $patron = '/^[a-zA-Z0-9.*, ]*$/';
            if (preg_match($patron, $cla)) {
                return true;
            } else {
                return false;
            }
        }
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
            $patron = '/^[a-zA-Z0-9, ]*$/';
            if (preg_match($patron, $cla)) {
                return true;
            } else {
                return false;
            }
        }
    }
    function validarTuit($tuit)
    {
        if (strlen($tuit) <= 140) {
            return true;
        } else {
            return false;
        }
    }