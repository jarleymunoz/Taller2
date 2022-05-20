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
    $conn = conexion();
    if (isset($_SESSION['usuario'])) {
        
        //Boton para ir al inicio 
        if (isset($_POST['lnkHome'])) {
            if (isset($_POST['anticsrf']) && isset($_SESSION['anticsrf']) && $_SESSION['anticsrf'] == $_POST['anticsrf'] || '0000' == $_POST['anticsrf']) {
                header("Location: inicio.php");
                
            } else {
                notificaciones('petición invalida');
            }
            
        }
        //Botón para salir del aplicativo y cerrar la sesión.
        if (isset($_POST['btnSalir'])) {
            if (isset($_POST['anticsrf']) && isset($_SESSION['anticsrf']) && $_SESSION['anticsrf'] == $_POST['anticsrf'] || '0000' == $_POST['anticsrf']) {
                session_destroy();
                header("Location: index.php");
            } else {
                notificaciones('petición invalida');
            }
            
        }
    } else {
        session_destroy();
        header("Location: index.php");
    }
   

    //datos del usuario actual
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
    <!-- partial:index.partial.html -->
    <div class="index">
        <div class="login-header">

            <form method="post">
                <p><input type="hidden" id="anticsrf" name="anticsrf" value="<?php echo $_SESSION['anticsrf'] ?>"></p>
                <input type="submit" name="lnkHome" class="login" value="Inicio">
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