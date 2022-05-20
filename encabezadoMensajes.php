<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title></title>
    <link rel="stylesheet" href="libs/style.css">

</head>

<body>
    <?php

    if (isset($_POST["lnkMensajes"])) {
        if (isset($_POST['anticsrf']) && isset($_SESSION['anticsrf']) && $_SESSION['anticsrf'] == $_POST['anticsrf'] || '0000' == $_POST['anticsrf']) {
            header("Location: mensajes.php");
        } else {
            notificaciones('petición invalida');
        }
    }

    if (isset($_POST["lnkEnviados"])) {
        if (isset($_POST['anticsrf']) && isset($_SESSION['anticsrf']) && $_SESSION['anticsrf'] == $_POST['anticsrf'] || '0000' == $_POST['anticsrf']) {
            header("Location: mensajesEnviados.php");
        } else {
            notificaciones('petición invalida');
        }
    }

    if (isset($_POST["lnkRecibidos"])) {
        if (isset($_POST['anticsrf']) && isset($_SESSION['anticsrf']) && $_SESSION['anticsrf'] == $_POST['anticsrf'] || '0000' == $_POST['anticsrf']) {
            header("Location: crearMensaje.php");
        } else {
            notificaciones('petición invalida');
        }
    }

    ?>
    <!-- partial:index.partial.html -->
    <div class="index">
        <div class="index input">
            <form method="post">
                <br>
                <p><input type="hidden" id="anticsrf" name="anticsrf" value="<?php echo $_SESSION['anticsrf'] ?>"></p>
                <input type="submit" class="login" name="lnkMensajes" value="Mensajes recibidos">

                <input type="submit" class="login" name="lnkEnviados" value="Mensajes enviados">

                <input type="submit" class="login" name="lnkRecibidos" value="Crear mensaje">
            </form>
        </div>
    </div>
    <!-- partial -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js" integrity="sha512-egJ/Y+22P9NQ9aIyVCh0VCOsfydyn8eNmqBy+y2CnJG+fpRIxXMS6jbWP8tVKp0jp+NO5n8WtMUAnNnGoJKi4w==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

</body>

</html>