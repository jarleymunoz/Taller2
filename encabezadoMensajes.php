<!DOCTYPE html>
<html lang="en" >
<head>
  <meta charset="UTF-8">
  <title></title>
  <link rel="stylesheet" href="libs/style.css">

</head>
<body>
<?php

if(isset($_POST["lnkMensajes"])){
    header("Location: mensajes.php");
}

if(isset($_POST["lnkEnviados"]))
{
    header("Location: mensajesEnviados.php");
}

if(isset($_POST["lnkRecibidos"]))
{
    header("Location: crearMensaje.php");
   
}

?>
<!-- partial:index.partial.html -->
 <div class="index">
     <div class="index input"> 
       <form method="post">
        <br>
         <input type="submit" class="login" name="lnkMensajes"value="Mensajes recibidos">
         
         <input type="submit" class="login" name="lnkEnviados"value="Mensajes enviados">
         
         <input type="submit" class="login" name="lnkRecibidos"value="Crear mensaje">
         </form>
    </div>
</div>
<!-- partial -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js" integrity="sha512-egJ/Y+22P9NQ9aIyVCh0VCOsfydyn8eNmqBy+y2CnJG+fpRIxXMS6jbWP8tVKp0jp+NO5n8WtMUAnNnGoJKi4w==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
  
</body>
</html>
