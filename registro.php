<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Formulario de registro</title>
  <link rel="stylesheet" href="libs/style.css">

</head>

<body>
  <?php
  require "libs/tools.php";
  require "libs/conexion.php";
  sesionSegura();
  $conn=conexion();

  //Boton de volver
  if (isset($_POST['btnVolver'])) {
    header("Location: index.php");
  }
  //Boton de registrar
  if (isset($_POST["btnRegistrar"])) {
    foreach ($_POST as $key => $value) {
      $_POST[$key] = Limpieza($value);
    }


    if (isset($_POST['anticsrf']) && isset($_SESSION['anticsrf']) && $_SESSION['anticsrf'] == $_POST['anticsrf']) {

      //asigno a nombre
      if (validarTexto($_POST['txtNombre']) == true) {
        $nombre = Limpieza($_POST["txtNombre"]);
      } else {
        notificaciones('Nombre inválido');
        $nombre = "";
      }
      //asigno a apellido
      if (validarTexto($_POST['txtApellidos']) == true) {
        $apellido = Limpieza($_POST["txtApellidos"]);
      } else {
        notificaciones('Apellido inválido');
        $apellido = "";
      }
      //asigno correo
      if (validarCorreo($_POST['txtCorreo']) == true) {
        $correo = Limpieza($_POST["txtCorreo"]);
      } else {
        notificaciones('Correo inválido');
        $correo = "";
      }
      //asigno dirección

      if ($_POST['txtDir'] == "") {
        notificaciones('Dirección inválida');
        $direccion = "";
      } else {
        $direccion = Limpieza($_POST["txtDir"]);
      }
      //asigno cantidad de hijos
      if (is_numeric($_POST['txtNumHij'])) {
        $hijos = Limpieza($_POST["txtNumHij"]);
      } else {
        notificaciones('Número de hijos inválido');
        $hijos = "";
      }
      //asigno a estado civil
      $estados = ['Soltero', 'Casado', 'Otro'];
      if (in_array($_POST['txtEstCivil'], $estados)) {
        $estadoCivil = Limpieza($_POST["txtEstCivil"]);
      } else {
        notificaciones('Estado civil inválido');
        $estadoCivil = "";
      }
      //asigno foto
      if (isset($_FILES['fulFoto']) && $_FILES['fulFoto']['error'] === UPLOAD_ERR_OK) {
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
      //asigno a usuario
      if (validarUsuario($_POST['txtUsuario'])) {
        $usuario = Limpieza($_POST["txtUsuario"]);
      } else {
        notificaciones('Usuario inválido');
        $usuario = "";
      }
      //asigno a clave
      if (validarClave($_POST['txtClave'])) {
        $clave = Limpieza($_POST["txtClave"]);
        $claveHash =  password_hash($clave, PASSWORD_DEFAULT);
      } else {
        notificaciones('Contraseña inválida');
        $clave = "";
        $claveHash = "";
      }

      if ($nombre != "" && $apellido != "" && $correo != "" && $direccion != "" && $hijos != "" && $estadoCivil != "" && $usuario != "" && $clave != "" && $claveHash != "") {
        //Busco el usuario en la base de datos para que no se repita
        $query = $conn->prepare("SELECT usuario 
                                 FROM usuario 
                                 WHERE usuario =:usuario");
        $res = $query->execute([
          'usuario' => $usuario
        ]);
        if ($res == true) {
          $usua =  $query->fetchAll(PDO::FETCH_OBJ);
          if (empty($usua)) {
            $query1 = $conn->prepare("INSERT INTO usuario (nombre, 
                                                           apellido,
                                                           correo,
                                                           direccion,
                                                           num_hijos,
                                                           estado_civil,
                                                           foto,
                                                           usuario,clave) 
                                      VALUES(:nombre,
                                             :apellido,
                                             :correo,
                                             :direccion,
                                             :num_hijos,
                                             :estado_civil,
                                             :foto,
                                             :usuario,
                                             :clave)");
            $res1 = $query1->execute([
              'nombre' => $nombre,
              'apellido' => $apellido,
              'correo' => $correo,
              'direccion' => $direccion,
              'num_hijos' => $hijos,
              'estado_civil' => $estadoCivil,
              'foto' => $dest_path,
              'usuario' => $usuario,
              'clave' => $claveHash
            ]);
            if ($res1 == true) {
              notificaciones('Usuario registrado correctamente');
              header("refresh:1;url: index.php");
            }
          } else {
            notificaciones('Usuario ya existe');
            header("refresh:2;url: registro.php");
          }
        }
      } else {
        notificaciones('Datos faltantes');
        header("refresh:2;url: registro.php");
      }
    } else {
      notificaciones('Petición invalida');
      header("refresh:2;url: registro.php");
    }
  }

  anticsrf();
  ?>
  <!-- partial:index.partial.html -->
  <div class="login">

    <h2 class="register-header">Registro</h2>

    <form class="register-container" method="post" enctype="multipart/form-data">
      <p><input type="hidden" id="anticsrf" name="anticsrf" value="<?php echo $_SESSION['anticsrf'] ?>"></p>
      <p><input type="text" placeholder="Nombre" id="txtNombre" name="txtNombre" pattern="[A-Za-z]+" required="required"></p>
      <p><input type="text" placeholder="Apellidos" id="txtApellidos" name="txtApellidos" pattern="[A-Za-z]+" required="required"></p>
      <p><input type="email" placeholder="Correo" id="txtCorreo" name="txtCorreo" required="required"></p>
      <p><input type="text" placeholder="Dirección" id="txtDir" name="txtDir" pattern="[A-Za-z0-9'\.\-\s\,]" required="required"></p>
      <p><input type="number" placeholder="Número Hijos" id="txtNumHij" name="txtNumHij" pattern="[0-9]+" required="required"></p>
      <p><select id="txtEstCivil" name="txtEstCivil" required="required">
          <option value="Soltero">Soltero</option>
          <option value="Casado">Casado</option>
          <option value="Otro">Otro</option>
        </select></p>
      <p>Foto de perfil (jpg,jpeg,png,gif)
        <input type="file" name="fulFoto" id="fulFoto" required="required">
      </p>
      <p><input type="text" placeholder="Usuario" id="txtUsuario" name="txtUsuario" pattern="[A-Za-z0-9]+" required="required"></p>
      <p><input type="password" placeholder="Clave" id="txtClave" name="txtClave" ></p>

      <p><input type="submit" value="Registrar" name="btnRegistrar"></p>
    </form>
    <form class="register-container" method="post" enctype="multipart/form-data">
      <p><input action="index.php" type="submit" value="Volver" name="btnVolver"></p>
    </form>
  </div>
  <!-- partial -->
  <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>

</body>

</html>