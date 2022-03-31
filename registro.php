<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Formulario de registro</title>
  <link rel="stylesheet" href="./style.css">

</head>

<body>
  <?php
  require "libs/tools.php";
  sesionSegura();
  $name = "";
  $lastName = "";
  $DateNac = "";
  $tipDoc = "";
  $numDoc = "";
  $numHi = "";
  $color = "";
  $user = "";
  $pass = "";
  //echo $anticsrf.'-'. $_SESSION['anticsrf'];
  ?>

  <?php


  if (isset($_POST["btnRegistrar"])) {
    foreach ($_POST as $key => $value) {
      $_POST[$key] = Limpieza($value);
    }
  

    if (isset($_POST['anticsrf']) && isset($_SESSION['anticsrf']) && $_SESSION['anticsrf'] == $_POST['anticsrf']) {
      //var_dump($_POST);
      //asigno a nombre
      if (validarTexto($_POST['txtName']) == true) {
        $name = $_POST["txtName"];
      } else {
        echo '<script language="javascript">alert("Nombre inválido");</script>';
      }
      //asigno a apellido
      if (validarTexto($_POST['txtLastName']) == true) {
        $lastName = $_POST["txtLastName"];
      } else {
        echo '<script language="javascript">alert("apellido inválido");</script>';
      }
      //asigno a fecha de nacimiento
      if (validarFecha($_POST['dateNac']) == true) {
        $DateNac = $_POST["dateNac"];
      } else {
        echo '<script language="javascript">alert("Fecha inválido");</script>';
      }
      //asigno a tipo de documento
      $estados = ['Cedula', 'TarjetaIdentidad', 'CedulaExtranjeria'];
      if (in_array($_POST['optTipDoc'], $estados)) {
        $tipDoc = $_POST["optTipDoc"];
      } else {
        echo '<script language="javascript">alert("Documento inválido");</script>';
      }
      //asigno a numero documento
      if (validarDocumento($_POST['txtNumDoc'])) {
        $numDoc = $_POST["txtNumDoc"];
      } else {
        echo '<script language="javascript">alert("Número documento inválido");</script>';
      }
      //cargue de imagen al servidor
      // Manejo de subida de un archivo
      if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['image']['tmp_name'];
        $fileName = $_FILES['image']['name'];
        $fileSize = $_FILES['image']['size'];
        $fileType = $_FILES['image']['type'];
        $fileNameCmps = explode(".", $fileName);
        $fileExtension = strtolower(end($fileNameCmps));
        $newFileName = md5(time() . $fileName) . '.' . $fileExtension;
        $allowedfileExtensions = array('jpg', 'gif', 'png', 'jpeg');
        if (in_array($fileExtension, $allowedfileExtensions)) {
          // directory in which the uploaded file will be moved
          $uploadFileDir = './files/';
          $dest_path = $uploadFileDir . $newFileName;
          if (move_uploaded_file($fileTmpPath, $dest_path)) {
            $img = imagecreatefromjpeg($dest_path);
            imagejpeg($img, $dest_path, 100);
            imagedestroy($img);
            $message = 'imgOk';
          }
        }
      } else {
        echo '<script language="javascript">alert("Imagen inválida");</script>';
      }
      //asigno cantidad de hijos
      if (is_numeric($_POST['txtNumHi'])) {
        $numHi = $_POST["txtNumHi"];
      } else {
        echo '<script language="javascript">alert("Número de hijos inválido");</script>';
      }
      //asigno a color de página
      if (validarColor($_POST['color']) == true) {
        $color = $_POST["color"];
      } else {
        echo '<script language="javascript">alert("Color inválido");</script>';
      }
      //asigno a usuario
      if (validarUsuario($_POST['txtUser'])) {
        $user = $_POST["txtUser"];
      } else {
        echo '<script language="javascript">alert("Usuario inválido");</script>';
      }
      //asigno a clave
      if (validarClave($_POST['txtPass'])) {
        $pass = md5($_POST["txtPass"]);
      } else {
        echo '<script language="javascript">alert("Contraseña inválida");</script>';
      }
      if (
        $user != "" && $pass != "" && $name != "" && $lastName != "" && $DateNac != "" && $color != "" &&
        $tipDoc != "" && $numDoc != "" && $numHi != ""
      ) {
        grabarUsuario($user, $pass, $name, $lastName, $DateNac, $tipDoc, $numDoc, $dest_path, $numHi, $color);
        header("location: Index.php");
      } else {
        header("location: Registro.php");
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
      <p><select id="txtEstCivil" name="txtEstCivil" >
          <option value="Soltero">Soltero</option>
          <option value="Casado">Casado</option>
          <option value="Otro">Otro</option>
        </select></p>
      <p>Foto de perfil
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