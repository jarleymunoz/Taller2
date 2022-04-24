<?php
require_once '../vendor/autoload.php'; //libreria de composer
require_once '../db/db.php'; //librerias de base datos
require_once '../libs/tools.php'; //libreria de funciones de validaciones

use Firebase\JWT\JWT; //libreria de jwt

$key = 'my_secret_key';
$time = time();
/*if (usuarioActual() == '') {
    echo 'Acceso no autorizado';
    http_response_code(401);
    exit();
}*/
if ($_SERVER['REQUEST_METHOD'] == 'GET') {

    $id = -1;
    if (isset($_GET['id_usuario'])) {
        $id = $_GET['id_usuario'];
    }
    $datos = misArticulos($id);
    header("HTTP/1.1 200 OK");
    echo json_encode($datos);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $articulo = Limpieza($_POST['txtMensaje']);
    $publico = Limpieza($_POST['chkPublico']);

    if (validarArticulo($articulo)) {
    } else {
        echo 'Artículo inválido';
        $articulo = "";
    }
    $estados = ['SI', 'NO'];
    if (in_array($publico, $estados)) {
    } else {
        echo 'Estado del artículo inválido';
        $publico = "";
    }

    if ($articulo != "" && $publico != "") {
        if(crearArticulo(/*usuarioActual()*/'Nenfer2022', $articulo, $publico)){
            echo 'Artículo creado';
            header("HTTP/1.1 200 OK");
            exit();
        }else{
            echo 'Artículo no pudo ser creado';
            http_response_code(401);
            exit();
        }
    } else {
        echo "Error, todos los campos deben estar llenos";
        http_response_code(401);
        exit();
    }
}
if ($_SERVER['REQUEST_METHOD'] == 'PUT') {

    
}
