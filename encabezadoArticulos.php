<!DOCTYPE html>
<html lang="en" >
<head>
  <meta charset="UTF-8">
  <title></title>
  <link rel="stylesheet" href="libs/style.css">

</head>
<body>
<?php

if(isset($_POST["lnkArticulos"])){
    header("Location: articulos.php");
}

if(isset($_POST["lnkMisArticulos"]))
{
    header("Location: misArticulos.php");
}

if(isset($_POST["lnkCrearArticulo"]))
{
    header("Location: crearArticulo.php");
   
}

?>
<!-- partial:index.partial.html -->
 <div class="index">
     <div class="index input"> 
       <form method="post">
        <br>
         <input type="submit" class="login" name="lnkArticulos"value="Todos los artículos">
         
         <input type="submit" class="login" name="lnkMisArticulos"value="Mis artículos">
         
         <input type="submit" class="login" name="lnkCrearArticulo"value="Crear artículo">
         </form>
    </div>
</div>
<!-- partial -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js" integrity="sha512-egJ/Y+22P9NQ9aIyVCh0VCOsfydyn8eNmqBy+y2CnJG+fpRIxXMS6jbWP8tVKp0jp+NO5n8WtMUAnNnGoJKi4w==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
  
</body>
</html>
