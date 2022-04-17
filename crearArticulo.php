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
    LimpiezaKV();

    if (!isset($_SESSION['usuario'])) {
        header("Location: index.php");
    }
    $idUsuarioActual = $_SESSION['usuario']['id'];
    if (isset($_POST['btnCrear'])) {

        if (isset($_POST['anticsrf']) && isset($_SESSION['anticsrf']) && $_SESSION['anticsrf'] == $_POST['anticsrf']) {
            if (($_POST['txtMensaje'])) {
                if (isset($_POST['chkPublico'])) {
                    //asigno a artículo
                    if (validarArticulo($_POST['txtMensaje']) == true) {
                        $articulo = Limpieza($_POST['txtMensaje']);
                        $publico = Limpieza($_POST['chkPublico']);
                        if ($publico == 'on') {
                            $query = $conn->prepare("INSERT INTO articulo (id_usuario, texto, publico) 
                                                 VALUES(:id_usuario,:texto,:publico)");
                            $res = $query->execute([
                                'id_usuario' => $idUsuarioActual,
                                'texto' => $articulo,
                                'publico' => 'SI'
                            ]);
                            if ($res == true) {
                                notificaciones('Artículo creado');
                                header("refresh:2;url=crearArticulo.php");
                            }
                        } else {
                            notificaciones('Artículo no creado');
                            header("refresh:2;url=crearArticulo.php");
                        }
                    } else {
                        notificaciones('Artículo inválido');
                    }
                } else {
                    //asigno a artículo
                    if (validarArticulo($_POST['txtMensaje']) == true) {
                        $articulo = Limpieza($_POST['txtMensaje']);
                        $query = $conn->prepare("INSERT INTO articulo (id_usuario, texto, publico) 
                                             VALUES(:id_usuario,:texto,:publico)");
                        $res = $query->execute([
                            'id_usuario' => $idUsuarioActual,
                            'texto' => $articulo,
                            'publico' => 'NO'
                        ]);
                        if ($res == true) {
                            notificaciones('Artículo creado');
                            header("refresh:2;url=crearArticulo.php");
                        }
                    } else {
                        notificaciones('Árticulo inválido');
                    }
                }
            } else {
                notificaciones('Texto inválido');
                header("refresh:2;url=crearArticulo.php");
            }
        } else {
            notificaciones('Petición invalida');
            header("refresh:2;url=crearArticulo.php");
        }
        anticsrf();
    }
    ?>
    <!-- partial:index.partial.html -->
    <div class="login">
        <div class="login-triangle"></div>

        <h2 class="login-header">Crear artículo</h2>

        <form class="login-container" method="post">
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