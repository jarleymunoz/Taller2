<!DOCTYPE html>
<html lang="en" >
<head>
  <meta charset="UTF-8">
  <title>Inicio</title>
  <link rel="stylesheet" href="libs/style.css">

</head>
<body>
<?php
//require "libs/tools.php";
require "libs/conexion.php";
require "encabezado.php";
//sesionSegura();
LimpiezaKV();

if(!isset($_SESSION['usuario']))
{
   header("Location: index.php");
}

if(isset($_POST["lnkArticulos"])){
    header("Location: articulos.php");
}

if(isset($_POST["lnkPerfil"]))
{
    header("Location: perfil.php");
}

if(isset($_POST["lnkMensajes"]))
{
    header("Location: mensajes.php");
   
}

?>
<!-- partial:index.partial.html -->
 <div class="index">
     <div class="index input"> 
       <form method="post">
        <br>
         <input type="submit" class="login" name="lnkArticulos"value="Ver artículos">
         
         <input type="submit" class="login" name="lnkMensajes"value="Ver mensajes">
         
         <input type="submit" class="login" name="lnkPerfil"value="Mi perfil">
         </form>
    </div>
</div>
<!-- partial -->
  <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>
  
</body>
</html>
