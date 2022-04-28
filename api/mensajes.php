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
//Envio de mensajes
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $destinatario = '';
    $mensaje = '';
    $adjunto = '';
    if (isset($_POST['destinatario'])) {
        $destinatario = Limpieza($_POST['destinatario']);
        //Valido destinatario
        if (validarUsuario($destinatario)) {
        } else {
            echo 'Destinatario inválido';
            $destinatario = "";
        }
    }
    if (isset($_POST['mensaje'])) {
        $mensaje = Limpieza($_POST['mensaje']);
        //valido el mensaje
        if (validarMensaje($mensaje)) {
        } else {
            echo 'Mensaje inválido';
            $mensaje = "";
        }
    }
    if (isset($_POST['adjunto'])) {
        $adjunto = Limpieza($_POST['adjunto']);
        //valido el adjunto
        $ruta = '../archivos/archivo_base64.jpg';
        $adjunto = base64_to_jpeg($adjunto, $ruta) . '';
    }
    if ($destinatario != '' && $mensaje != '') {
        if (enviarMensaje(usuarioActual(), $destinatario, $mensaje, $adjunto)) {
            echo 'Mensaje enviado';
            header("HTTP/1.1 200 OK");
            exit();
        } else {
            echo 'Mensaje no pudo ser enviado';
            http_response_code(401);
            exit();
        }
    } else {
        echo 'Campos incorrectos';
        http_response_code(401);
        exit();
    }
}
