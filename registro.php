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

  if (isset($_POST["btnRegistrar"])) {
    foreach ($_POST as $key => $value) {
      $_POST[$key] = Limpieza($value);
    }


    if (isset($_POST['anticsrf']) && isset($_SESSION['anticsrf']) && $_SESSION['anticsrf'] == $_POST['anticsrf']) {

      //asigno a nombre
      if (validarTexto($_POST['txtNombre']) == true) {
        $nombre = Limpieza($_POST["txtNombre"]);
      } else {
        echo '<script language="javascript">alert("Nombre inválido");</script>';
        $nombre = "";
      }
      //asigno a apellido
      if (validarTexto($_POST['txtApellidos']) == true) {
        $apellido = Limpieza($_POST["txtApellidos"]);
      } else {
        echo '<script language="javascript">alert("apellido inválido");</script>';
        $apellido = "";
      }
      //asigno correo
      if (validarCorreo($_POST['txtCorreo']) == true) {
        $correo = Limpieza($_POST["txtCorreo"]);
      } else {
        echo '<script language="javascript">alert("correo inválido");</script>';
        $correo = "";
      }
      //asigno dirección

      if ($_POST['txtDir'] == "") {
        echo '<script language="javascript">alert("dirección inválida");</script>';
        $direccion = "";
      } else {
        $direccion = Limpieza($_POST["txtDir"]);
      }
      //asigno cantidad de hijos
      if (is_numeric($_POST['txtNumHij'])) {
        $hijos = Limpieza($_POST["txtNumHij"]);
      } else {
        echo '<script language="javascript">alert("Número de hijos inválido");</script>';
        $hijos = "";
      }
      //asigno a estado civil
      $estados = ['Soltero', 'Casado', 'Otro'];
      if (in_array($_POST['txtEstCivil'], $estados)) {
        $estadoCivil = Limpieza($_POST["txtEstCivil"]);
      } else {
        echo '<script language="javascript">alert("estado civil inválido");</script>';
        $estadoCivil = "";
      }
      //asigno foto
      if (isset($_FILES['fulFoto']) && $_FILES['fulFoto']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['fulFoto']['tmp_name'];
        $fileName = $_FILES['fulFoto']['name'];
        $fileNameCmps = explode(".", $fileName);
        $fileExtension = strtolower(end($fileNameCmps));
        $newFileName = md5(time() . $fileName) . '.' . $fileExtension;
        $allowedfileExtensions = array('jpg', 'jpeg');
        if (in_array($fileExtension, $allowedfileExtensions)) {
          // directory in which the uploaded file will be moved
          $uploadFileDir = './img/';
          $dest_path = $uploadFileDir . $newFileName;
          if (move_uploaded_file($fileTmpPath, $dest_path)) {
            $img = imagecreatefromjpeg($dest_path);
            imagejpeg($img, $dest_path, 100);
            imagedestroy($img);
          }
        } else {
          echo '<script language="javascript">alert("Imagen no válida");</script>';
        }
      } else {
        $dest_path = '';
      }
      //asigno a usuario
      if (validarUsuario($_POST['txtUsuario'])) {
        $usuario = Limpieza($_POST["txtUsuario"]);
      } else {
        echo '<script language="javascript">alert("Usuario inválido");</script>';
        $usuario = "";
      }
      //asigno a clave
      if (validarClave($_POST['txtClave'])) {
        $clave = Limpieza($_POST["txtClave"]);
        $claveHash =  password_hash($clave, PASSWORD_DEFAULT);
      } else {
        echo '<script language="javascript">alert("Contraseña inválida");</script>';
        $clave = "";
        $claveHash = "";
      }

      if ($nombre != "" && $apellido != "" && $correo != "" && $direccion != "" && $hijos != "" && $estadoCivil != "" && $usuario != "" && $clave != "" && $claveHash != "") {

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
              header("Location: index.php");
            }
          } else {
            echo '<script language="javascript">alert("Usuario está en uso");</script>';
          }
        }
      } else {
        echo '<script language="javascript">alert("Datos faltantes");</script>';
      }
    } else {
      echo '<script language="javascript">alert("Petición inválida");</script>';
    }
  }

  anticsrf();
  ?>
  <!-- partial:index.partial.html -->
  <div class="login">

    <h2 class="register-header">Registro</h2>

    <form class="register-container" method="post" enctype="multipart/form-data">
      <p><input type="hidden" id="anticsrf" name="anticsrf" value="<?php echo $_SESSION['anticsrf'] ?>"></p>
      <p><input type="text" placeholder="Nombre" id="txtNombre" name="txtNombre"></p>
      <p><input type="text" placeholder="Apellidos" id="txtApellidos" name="txtApellidos"></p>
      <p><input type="text" placeholder="Correo" id="txtCorreo" name="txtCorreo"></p>
      <p><input type="text" placeholder="Dirección" id="txtDir" name="txtDir"></p>
      <p><input type="text" placeholder="Número Hijos" id="txtNumHij" name="txtNumHij"></p>
      <p><select id="txtEstCivil" name="txtEstCivil">
          <option value="Soltero">Soltero</option>
          <option value="Casado">Casado</option>
          <option value="Otro">Otro</option>
        </select></p>
      <p>Foto de perfil (jpg,jpeg)
        <input type="file" name="fulFoto" id="fulFoto">
      </p>
      <p><input type="text" placeholder="Usuario" id="txtUsuario" name="txtUsuario"></p>
      <p><input type="password" placeholder="Clave" id="txtClave" name="txtClave"></p>

      <p><input type="submit" value="Registrar" name="btnRegistrar"></p>
    </form>
  </div>
  <!-- partial -->
  <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>

</body>

</html>