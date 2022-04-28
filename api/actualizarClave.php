<?php
require_once '../vendor/autoload.php'; //libreria de composer
require_once '../db/db.php'; //librerias de base datos
require_once '../libs/tools.php'; //libreria de funciones de validaciones

use Firebase\JWT\JWT; //libreria de jwt

$key = 'my_secret_key';
$time = time();

if (usuarioActual() == '') {
    echo 'Acceso no autorizado';
    http_response_code(401);
    exit();
}
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $claveAnterior='';
    $claveNueva='';
    $claveRepetir='';
    if (isset($_POST['claveAnterior'])) {
        $claveAnterior = Limpieza($_POST['claveAnterior']);
    }
    if (isset($_POST['claveNueva'])) {
        $claveNueva = Limpieza($_POST['claveNueva']);
    }
    if (isset($_POST['claveRepetir'])) {
        $claveRepetir = Limpieza($_POST['claveRepetir']);
    }
    if ($claveAnterior != '' && $claveNueva != '' && $claveRepetir != '') {
        if (validarClave($claveAnterior) && validarClave($claveNueva) && validarClave($claveRepetir)) {
            $ok = actualizaClave($claveAnterior, $claveNueva, $claveRepetir, usuarioActual());
            if ($ok) {
                echo 'Clave actualizada: ' . usuarioActual();
                header("HTTP/1.1 200 OK");
                exit();
            } else {
                echo 'Clave no actualizada';
                http_response_code(401);
                exit();
            }
        } else {
            echo 'Contraseña inválida';
            http_response_code(401);
            exit();
        }
    } else {
        echo 'Se deben llenar todos los campos';
        http_response_code(401);
        exit();
    }
}
