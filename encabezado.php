<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Inicio</title>
    <link rel="stylesheet" href="libs/style.css">

</head>

<body>
    <?php
    require "libs/tools.php";
    require "libs/conexion.php";
    sesionSegura();
    //limpieza de llave valor del $_POST
    LimpiezaKV();
  
    if (isset($_SESSION['usuario'])) {
        //Botn para ir al inicio 
        if (isset($_POST['lnkHome'])) {
            header("Location: inicio.php");
        }
          
        //Botón para salir del aplicativo y cerrar la sesión.
        if (isset($_POST['btnSalir'])) {
            session_destroy();
            header("Location: index.php");
        }
    } else {
        header("Location: index.php");
    }
    
    
    ?>
    <!-- partial:index.partial.html -->
    <div class="index">
        <div class="login-header"> 
            <form method="post">
            <input type="submit" name="lnkHome" class="login" value="Inicio">
            <img name="imgFoto" src="<?php echo $_SESSION['usuario']['foto'] ?>" right="100" width="100">
            <label name="lblNombre"><?php echo $_SESSION['usuario']['nombre'] ?> </label>
            <input type="submit" class="login" name="btnSalir" value="Salir">
            </form>
        </div>
    </div>
    <!-- partial -->
    <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>
</body>

</html>