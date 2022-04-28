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
//Obtener los articulos de un usuario
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $id = -1;
    if (isset($_GET['id_usuario'])) {
        $id = $_GET['id_usuario'];
    }
    $datos = misArticulos($id);
    header("HTTP/1.1 200 OK");
    echo json_encode($datos);
}

//Creación de un artículo
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $articulo = '';
    $publico = '';
    if (isset($_POST['txtMensaje'])) {
        $articulo = Limpieza($_POST['txtMensaje']);
        if (validarArticulo($articulo)) {
        } else {
            echo 'Artículo inválido';
            $articulo = "";
        }
    }
    if (isset($_POST['chkPublico'])) {
        $publico = Limpieza($_POST['chkPublico']);
        $estados = ['SI', 'NO'];
        if (in_array($publico, $estados)) {
        } else {
            echo 'Estado del artículo inválido';
            $publico = "";
        }
    }
    if ($articulo != "" && $publico != "") {
        if (crearArticulo(usuarioActual(), $articulo, $publico)) {
            echo 'Artículo creado';
            header("HTTP/1.1 200 OK");
            exit();
        } else {
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

//Modificación del estado publicado de un artículo por su ID
if ($_SERVER['REQUEST_METHOD'] == 'PUT') {

    if (isset($_GET['id_articulo']) && isset($_GET['publico'])) {

        $id_articulo = Limpieza($_GET['id_articulo']);
        $publico = Limpieza($_GET['publico']);
        $estados = ['SI', 'NO'];
        if (is_numeric($id_articulo) && in_array($publico, $estados)) {
            if (actualizarEstadoArticulo($id_articulo, $publico)) {
                echo 'Artículo modificado';
                header("HTTP/1.1 200 OK");
                exit();
            } else {
                echo 'Artículo no pudo ser modificado';
                http_response_code(401);
                exit();
            }
        } else {
            echo 'Id o estado del artículo inválido';
            http_response_code(401);
            exit();
        }
    }
}

//Eliminación de un articulo por su ID
if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {

    if (isset($_GET['id_articulo'])) {

        $id_articulo = Limpieza($_GET['id_articulo']);
        if (is_numeric($id_articulo)) {
            if (eliminarArticulo($id_articulo)) {
                echo 'Artículo eliminado';
                header("HTTP/1.1 200 OK");
                exit();
            } else {
                echo 'Artículo no pudo ser eliminado';
                http_response_code(401);
                exit();
            }
        } else {
            echo 'Id del artículo inválido';
            http_response_code(401);
            exit();
        }
    }
}
