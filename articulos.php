<!DOCTYPE html>
<html lang="en" >
<head>
  <meta charset="UTF-8">
  <title>Artículos</title>
  <link rel="stylesheet" href="libs/style.css">

</head>
<body>
<?php
require "encabezado.php";
require "encabezadoArticulos.php";
//limpieza de llave valor del $_POST
LimpiezaKV();

if(!isset($_SESSION['usuario']))
{
   header("Location: index.php");
}
//Query que trae todos los articulos públicos.
 $query = $conn->prepare("SELECT u.nombre, u.foto, a.texto, a.fecha_publi 
                          FROM articulo a 
                          JOIN usuario u ON a.id_usuario = u.id_usuario 
                          WHERE a.publico = 'SI'
                          order by a.fecha_publi desc");
 $res = $query->execute();
 if($res==true)
 {
     $articulos = $query->fetchAll(PDO::FETCH_OBJ); 
    ?>
<?php 
    foreach ($articulos as $data)
    {
?>


<!-- partial:index.partial.html -->
 <div class="index">
     <div class="index input"> 
       <form method="post">
        <br>
        <label name="lblAutor_1"><?php echo $data->nombre;?> </label>
        <br>
        <img name="imgFotoAutor_1" src="<?php echo $data->foto ?>"  right="100" width="100">
         
        <label name="lblTexto_1"><?php echo $data->texto;?> </label>
        <br>
        <label name="lblFecha_1"><?php echo $data->fecha_publi;?> </label>
         </form>
    </div>
</div>
<!-- partial -->
  <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>
  
</body>
</html>
<?php
    //fin foreach
    }
?>
<?php
//fin if
}      
?>