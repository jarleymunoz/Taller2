<?php

require_once '../vendor/autoload.php'; //libreria de composer
require_once '../db/db.php'; //librerias de base datos
require_once '../libs/tools.php'; //libreria de funciones de validaciones

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = Limpieza($_POST["txtNombre"]);
    $apellido = Limpieza($_POST["txtApellidos"]);
    $correo = Limpieza($_POST["txtCorreo"]);
    $direccion = Limpieza($_POST["txtDir"]);
    $hijos = Limpieza($_POST["txtNumHij"]);
    $estadoCivil = Limpieza($_POST["txtEstCivil"]);
    $usuario = Limpieza($_POST["txtUsuario"]);
    $clave = Limpieza($_POST["txtClave"]);
    $rutahas='';
    //asigno a nombre
    if (validarTexto($_POST['txtNombre']) == true) {
    } else {
        echo 'Nombre inválido';
        $nombre = "";
    }
    //asigno a apellido
    if (validarTexto($_POST['txtApellidos']) == true) {
    } else {
        echo 'Apellido inválido';
        $apellido = "";
    }
    //asigno correo
    if (validarCorreo($_POST['txtCorreo']) == true) {
    } else {
        echo 'Correo inválido';
        $correo = "";
    }
    //asigno dirección
    if ($_POST['txtDir'] == "") {
        echo 'Dirección inválida';
        $direccion = "";
    }
    //asigno cantidad de hijos
    if (is_numeric($_POST['txtNumHij'])) {
    } else {
        echo 'Número de hijos inválido';
        $hijos = "";
    }
    //asigno a estado civil
    $estados = ['Soltero', 'Casado', 'Otro'];
    if (in_array($_POST['txtEstCivil'], $estados)) {
    } else {
        echo 'Estado civil inválido';
        $estadoCivil = "";
    }
    //asigno foto
    /*if (isset($_FILES['fulFoto']) && $_FILES['fulFoto']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['fulFoto']['tmp_name'];
        $fileName = $_FILES['fulFoto']['name'];
        $fileNameCmps = explode(".", $fileName);
        $fileExtension = strtolower(end($fileNameCmps));
        $newFileName = md5(time() . $fileName) . '.' . $fileExtension;
        $allowedfileExtensions = array('jpg', 'jpeg', 'png', 'gif');
        if (in_array($fileExtension, $allowedfileExtensions)) {
          // directory in which the uploaded file will be moved
          $uploadFileDir = './img/';
          $dest_path = $uploadFileDir . $newFileName;
          if (move_uploaded_file($fileTmpPath, $dest_path)) {
            if ($fileExtension == 'jpeg' || $fileExtension == 'jpg') {
              $img = imagecreatefromjpeg($dest_path);
              imagejpeg($img, $dest_path, 100);
              imagedestroy($img);
            } else if ($fileExtension == 'png') {
              $img = imagecreatefrompng($dest_path);
              imagepng($img, $dest_path, 9);
              imagedestroy($img);
            } else if ($fileExtension == 'gif') {
              $img = imagecreatefromgif($dest_path);
              imagegif($img, $dest_path, 100);
              imagedestroy($img);
            }
          }
        } else {
          notificaciones('Imagen no válida');
        }
      } else {
        $dest_path = '';
      }
      */
    //asigno a usuario
    if (validarUsuario($_POST['txtUsuario'])) {
    } else {
        echo 'Usuario inválido';
        $usuario = "";
    }
    //asigno a clave
    if (validarClave($_POST['txtClave'])) {
        $claveHash =  password_hash($clave, PASSWORD_DEFAULT);
    } else {
        echo 'Contraseña inválida';
        $clave = "";
        $claveHash = "";
    }
    
    if (
        $nombre != "" && $apellido != "" && $correo != "" && $direccion != "" && $hijos != "" && $estadoCivil != ""
        && $usuario != "" && $clave != "" && $claveHash != ""
    ) {
        $usuariobd = buscarUsuario($usuario);
        if (empty($usuariobd)) {

            if (registroUsuario($nombre, $apellido, $correo, $direccion, $hijos, $estadoCivil, $rutahas, $usuario,  $claveHash)) {
                echo 'Usuario creado:'.$usuario;
                header("HTTP/1.1 200 OK");
                exit();
            }else{
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
}
