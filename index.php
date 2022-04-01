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
  sesionSegura();

  $user = "";
  $pass = "";
  ?>


  <?php
 
 /* if (isset($_POST["btnLogin"])) {
    foreach ($_POST as $key => $value) {
      $_POST[$key] = Limpieza($value);
    }
    if (isset($_POST['anticsrf']) && isset($_SESSION['anticsrf']) && $_SESSION['anticsrf'] == $_POST['anticsrf']) {
      if (validarUsuario($_POST['txtUser']) == true && validarClave($_POST['txtPass']) == true) {
        $user = $_POST["txtUser"];
        $pass = $_POST["txtPass"];
        if (leer($user, $pass) == true) {
          if ((!isset($_SESSION["user"])) && (!isset($_SESSION["pass"]))) {
            $_SESSION["user"] = $user;
            $_SESSION["pass"] = $pass;

          } else {
            $user = $_POST["txtUser"];
            $pass = $_POST["txtPass"];
              }
          header("location: Index2.php");
        } else {
          echo '<script language="javascript">alert("Datos incorrectos");</script>';
        }
      } else {
        echo '<script language="javascript">alert("Datos inválidos");</script>';
        header("location: Login.php");
      }
    } else {
      echo '<script language="javascript">alert("Petición inválida");</script>';
    }
  }
  anticsrf();*/

  if(isset($_POST["btnRegistrar"]))
  {
     header("Location: registro.php");
  }
  ?>
  <!-- partial:index.partial.html -->
  <div class="login">
    <div class="login-triangle"></div>

    <h2 class="login-header">Ingreso</h2>

    <form class="login-container" method="post">
      <p><input type="hidden" id="anticsrf" name="anticsrf" value="<?php echo $_SESSION['anticsrf'] ?>"></p>
      <p><input type="text" name="txtUsuario" id="txtUsuario" placeholder="Usuario" ></p>
      <p><input type="password" name="txtClave" id="txtClave" placeholder="Clave" ></p>
      <p><input type="submit" name="btnIngresar" value="Ingresar"></p>
      <p><input type="submit" name="btnRegistrar" value="Registrar"></p>
    </form>
  </div>
  <!-- partial -->
  <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>
</body>

</html>