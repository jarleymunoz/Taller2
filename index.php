<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Ingreso</title>
  <link rel="stylesheet" href="libs/style.css">

</head>

<body>
  <?php
  require "libs/tools.php";
  require "libs/conexion.php";
  sesionSegura();

  //Botón de Ingresar
  if (isset($_POST["btnIngresar"])) {
    foreach ($_POST as $key => $value) {
      $_POST[$key] = Limpieza($value);
    }
    if (isset($_POST['anticsrf']) && isset($_SESSION['anticsrf']) && $_SESSION['anticsrf'] == $_POST['anticsrf']) {
      if (validarUsuario($_POST['txtUsuario']) == true && validarClave($_POST['txtClave']) == true) {
        $usuario = Limpieza($_POST["txtUsuario"]);
        $clave = Limpieza($_POST["txtClave"]);

        $query = $conn->prepare("SELECT id_usuario,nombre,usuario,clave,foto FROM usuario WHERE usuario =?");
        $res = $query->execute([$usuario]);
        if ($res == true) {
          $usuar = $query->fetchAll(PDO::FETCH_OBJ);
          if (!empty($usuar)) {
            foreach ($usuar as $data) {
              $id = $data->id_usuario;
              $nombre = $data->nombre;
              $user = $data->usuario;
              $pass = $data->clave;
              $foto = $data->foto;
            }

            if (password_verify($clave, $pass)) {
              $_SESSION['usuario']['usuario'] = $user;
              $_SESSION['usuario']['nombre'] = $nombre;
              $_SESSION['usuario']['id']     = $id;
              $_SESSION['usuario']['foto']   = $foto;

              header("Location: inicio.php");
            } else {
              echo '<script language="javascript">alert("Clave incorrecta.");</script>';
            }
          } else {
            echo '<script language="javascript">alert("Usuario no encontrado");</script>';
          }
        }
      } else {
        echo '<script language="javascript">alert("Datos incorrectos");</script>';
      }
    } else {
      echo '<script language="javascript">alert("Petición inválida");</script>';
    }
  }
  //Botón de Registro
  if (isset($_POST["btnRegistrar"])) {
    header("Location: registro.php");
  }
  anticsrf();
  ?>
  <!-- partial:index.partial.html -->
  <div class="login">
    <div class="login-triangle"></div>

    <h2 class="login-header">Ingreso</h2>

    <form class="login-container" method="post">
      <p><input type="hidden" id="anticsrf" name="anticsrf" value="<?php echo $_SESSION['anticsrf'] ?>"></p>
      <p><input type="text" name="txtUsuario" id="txtUsuario" placeholder="Usuario"></p>
      <p><input type="password" name="txtClave" id="txtClave" placeholder="Clave"></p>
      <p><input type="submit" name="btnIngresar" value="Ingresar"></p>
      <p><input type="submit" name="btnRegistrar" value="Registrar"></p>
    </form>
  </div>
  <!-- partial -->
  <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>
</body>

</html>