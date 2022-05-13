<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Inicio</title>
    <link rel="stylesheet" href="libs/style.css">

</head>

<body>
    <?php
    require_once "libs/tools.php";
    require_once "libs/conexion.php";
    sesionSegura();
    //limpieza de llave valor del $_POST
    LimpiezaKV();
    $conn=conexion();
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
        session_destroy();
        header("Location: index.php");
    }
    
    
    ?>
    <!-- partial:index.partial.html -->
    <div class="index">
        <div class="login-header"> 
            <form method="post">
            <input type="submit" name="lnkHome" class="login" value="Inicio">
            <?php
                $idUsuarioActual = $_SESSION['usuario']['id'];
                $query = $conn->prepare("SELECT nombre, foto 
                FROM usuario WHERE id_usuario=:id_usuario ");
                $res = $query->execute([
                'id_usuario' => $idUsuarioActual
                ]);
                $datos = $query->fetch(PDO::FETCH_BOTH);
                $_SESSION['usuario']['nombre'] = $datos[0];
                $_SESSION['usuario']['foto'] = $datos[1];
            ?>
            <img name="imgFoto" src="<?php echo $_SESSION['usuario']['foto'] ?>" right="100" width="100">
            <label name="lblNombre"><?php echo $_SESSION['usuario']['nombre'] ?> </label>
            <input type="submit" class="login" name="btnSalir" value="Salir">
            </form>
        </div>
    </div>
    <!-- partial -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js" integrity="sha512-egJ/Y+22P9NQ9aIyVCh0VCOsfydyn8eNmqBy+y2CnJG+fpRIxXMS6jbWP8tVKp0jp+NO5n8WtMUAnNnGoJKi4w==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
</body>

</html>