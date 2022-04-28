<?php

require_once '../vendor/autoload.php'; //libreria de composer
require_once '../db/db.php'; //librerias de base datos
require_once '../libs/tools.php'; //libreria de funciones de validaciones

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = '';
    $apellido = '';
    $correo = '';
    $direccion = '';
    $hijos = '';
    $estadoCivil = '';
    $imagen = '';
    $usuario = '';
    $clave = '';
    //asigno nombre
    if (isset($_POST["txtNombre"])) {
        $nombre = Limpieza($_POST["txtNombre"]);
        if (validarTexto($nombre)) {
        } else {
            echo 'Nombre inválido';
            $nombre = "";
        }
    }
    //asigno apellido
    if (isset($_POST["txtApellidos"])) {
        $apellido = Limpieza($_POST["txtApellidos"]);
        if (validarTexto($apellido)) {
        } else {
            echo 'Apellido inválido';
            $nombre = "";
        }
    }
    //asigno correo
    if (isset($_POST["txtCorreo"])) {
        $correo = Limpieza($_POST["txtCorreo"]);
        if (validarCorreo($correo)) {
        } else {
            echo 'Correo inválido';
            $correo = "";
        }
    }
    //asigno dirección
    if (isset($_POST["txtDir"])) {
        $direccion = Limpieza($_POST["txtDir"]);
        if (validarDireccion($direccion)) {
        } else {
            echo 'Dirección inválida';
            $direccion = "";
        }
    }
    //asigno cantidad de hijos
    if (isset($_POST["txtNumHij"])) {
        $hijos = Limpieza($_POST["txtNumHij"]);
        if (is_numeric($hijos)) {
        } else {
            echo 'Número de hijos inválido';
            $hijos = "";
        }
    }
    //asigno a estado civil
    if (isset($_POST["txtEstCivil"])) {
        $estadoCivil = Limpieza($_POST["txtEstCivil"]);
        $estados = ['Soltero', 'Casado', 'Otro'];
        if (in_array($estadoCivil, $estados)) {
        } else {
            echo 'Estado civil inválido';
            $estadoCivil = "";
        }
    }
    //asigno foto
    if (isset($_POST["imagen"])) {
        $imagen = Limpieza($_POST["imagen"]);
        $ruta = '../img/imagen_base64.jpg';
        $imagen = base64_to_jpeg($imagen, $ruta);
    }
    //asigno a usuario
    if (isset($_POST["txtUsuario"])) {
        $usuario = Limpieza($_POST["txtUsuario"]);
        if (validarUsuario($_POST['txtUsuario'])) {
        } else {
            echo 'Usuario inválido';
            $usuario = "";
        }
    }
    //asigno a clave
    if (isset($_POST["txtClave"])) {
        $clave = Limpieza($_POST["txtClave"]);
        if (validarClave($_POST['txtClave'])) {
            $claveHash =  password_hash($clave, PASSWORD_DEFAULT);
        } else {
            echo 'Contraseña inválida';
            $clave = "";
            $claveHash = "";
        }
    }
}
//Valido que todos los campos estén llenos
if (
    $nombre != "" && $apellido != "" && $correo != "" && $direccion != "" && $hijos != "" && $estadoCivil != ""
    && $usuario != "" && $clave != "" && $claveHash != ""
) {
    $usuariobd = buscarUsuario($usuario);
    if (empty($usuariobd)) {

        if (registroUsuario($nombre, $apellido, $correo, $direccion, $hijos, $estadoCivil, $imagen, $usuario,  $claveHash)) {
            echo 'Usuario creado: ' . $usuario;
            header("HTTP/1.1 200 OK");
            exit();
        } else {
            echo "Error el usuario no creado";
            http_response_code(401);
            exit();
        }
    } else {
        echo "Error el usuario está en uso";
        http_response_code(401);
        exit();
    }
} else {
    echo "Error todos los campos deben estar llenos";
    http_response_code(401);
    exit();
}
