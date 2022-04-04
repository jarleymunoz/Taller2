<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Crear artículo</title>
    <link rel="stylesheet" href="libs/style.css">

</head>

<body>
    <?php
    require "encabezado.php";
    require "encabezadoArticulos.php";


    if (!isset($_SESSION['usuario'])) {
        header("Location: index.php");
    }

    if (isset($_POST['btnCrear'])) {
        LimpiezaKV();
        if (isset($_POST['anticsrf']) && isset($_SESSION['anticsrf']) && $_SESSION['anticsrf'] == $_POST['anticsrf']) {
            //Falta crear función que limpie el texto
            if (($_POST['txtMensaje'])) {
                if (isset($_POST['chkPublico'])) {
                    $articulo = Limpieza($_POST['txtMensaje']);
                    $publico = Limpieza($_POST['chkPublico']);
                    $query = $conn->prepare("INSERT INTO articulo (id_usuario, texto, publico) VALUES(?,?,?)");
                    $res = $query->execute([$_SESSION['usuario']['id'], $articulo, 'SI']);
                    if ($res == true) {
                        echo '<script language="javascript">alert("Artículo creado");</script>';
                    }
                } else {
                    $articulo = Limpieza($_POST['txtMensaje']);
                    $query = $conn->prepare("INSERT INTO articulo (id_usuario, texto, publico) VALUES(?,?,?)");
                    $res = $query->execute([$_SESSION['usuario']['id'], $articulo, 'NO']);
                    if ($res == true) {
                        echo '<script language="javascript">alert("Artículo creado");</script>';
                    }
                }
            }
            else {
                echo '<script language="javascript">alert("Texto inválido");</script>';
            }
        } else {
            echo '<script language="javascript">alert("Petición inválida");</script>';
        }
        anticsrf();
    }
    ?>
    <!-- partial:index.partial.html -->
    <div class="login">
        <div class="login-triangle"></div>

        <h2 class="login-header">Crear artículo</h2>

        <form class="login-container" method="post" >
            <p><input type="hidden" id="anticsrf" name="anticsrf" value="<?php echo $_SESSION['anticsrf'] ?>"></p>
            <p><textarea name="txtMensaje" id="txtMensaje" placeholder="Escribir artículo" rows="20" cols="41" maxlength="140"></textarea></p>
            <p>¿Es público?<input type="checkbox" name="chkPublico"></p>
            <p><input type="submit" name="btnCrear" value="Crear artículo"></p>
        </form>
    </div>
    <!-- partial -->
    <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>
</body>

</html>