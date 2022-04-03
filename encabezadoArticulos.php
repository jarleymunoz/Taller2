<!DOCTYPE html>
<html lang="en" >
<head>
  <meta charset="UTF-8">
  <title>Inicio</title>
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
  <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>
  
</body>
</html>
