<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Formulario de reCaptcha</title>
    <link rel="stylesheet" href="libs/style.css">
    <script src = "https://www.google.com/recaptcha/api.js"async defer></script>

</head>

<body>
    <?php
    require "libs/tools.php";
    require "libs/conexion.php";
    sesionSegura();
    LimpiezaKV();
    $conn = conexion();
    //Botón de Ingresar
    if (isset($_POST["btnIngresar"])) {
        $secretKey = "6LcinMkfAAAAALox4vSVPfk_EJ69cLDrQV7Hr1Cg";
        $captcha = $_POST["g-recaptcha-response"];
        $ip = $_SERVER['REMOTE_ADDR'];
        
        //Chequear captcha con google
        $response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=" .
            $secretKey . "&response=" . $captcha . "&remoteip" . $ip);
        
            var_dump($response);
        $responseKeys = json_decode($response, true);
        
        if (isset($_POST['anticsrf']) && isset($_SESSION['anticsrf']) && $_SESSION['anticsrf'] == $_POST['anticsrf']) {
            if (validarUsuario($_POST['txtUsuario']) == true && validarClave($_POST['txtClave']) == true) {

                if (intval($responseKeys["success"]) == 1) {
                    

                    $usuario = Limpieza($_POST["txtUsuario"]);
                    $clave = Limpieza($_POST["txtClave"]);

                    $query = $conn->prepare("SELECT id_usuario,nombre,usuario,clave,foto 
                                 FROM usuario 
                                 WHERE usuario =:usuario");
                    $res = $query->execute([
                        'usuario' => $usuario
                    ]);
                    if ($res == true) {
                        $usuar = $query->fetchAll(PDO::FETCH_OBJ);
                        if (!empty($usuar)) {
                            foreach ($usuar as $data) {
                                $id = $data->id_usuario;
                                $nombre = $data->nombre;
                                $user = $data->usuario;
                                $pass = $data->clave;
                                $foto = $data->foto;
                            }

                            if (password_verify($clave, $pass)) {
                                $_SESSION['usuario']['usuario'] = $user;
                                $_SESSION['usuario']['nombre'] = $nombre;
                                $_SESSION['usuario']['id']     = $id;
                                $_SESSION['usuario']['foto']   = $foto;
                                notificaciones('Datos válidos');
                                header("refresh:2;url=inicio.php");
                            } else {
                                notificaciones('Clave incorrecta');
                            }
                        } else {
                            notificaciones('No se encuentra el usuario');
                        }
                    }
                } else {
                    echo "no se valido captcha";
                }
            } else {
                notificaciones('Datos incorrectos');
            }
        } else {
            notificaciones('Petición invalida');
            header("refresh:2;url=loginsec.php");
        }
    }
    //Botón de Registro
    if (isset($_POST["btnRegistrar"])) {
        header("Location: registro.php");
    }
    anticsrf();
    ?>
    <!-- partial:index.partial.html -->
    <div class="index">
        <h2 class="integrantes-header">Ricardo A. Triviño - Jose A. Muñoz</h2>
    </div>
    <div class="login">
        <div class="login-triangle"></div>

        <h2 class="login-header">Ingreso</h2>

        <form class="login-container" method="post">
            <p><input type="hidden" id="anticsrf" name="anticsrf" value="<?php echo $_SESSION['anticsrf'] ?>"></p>
            <p><input type="text" name="txtUsuario" id="txtUsuario" placeholder="Usuario" pattern="[A-Za-z0-9]+" required="required"></p>
            <p><input type="password" name="txtClave" id="txtClave" placeholder="Clave" pattern="^\S*(?=\S{8,})(?=\S*[a-z])(?=\S*[A-Z])(?=\S*[\d])\S*$" required="required"></p>
            <p><div class="g-recaptcha" data-sitekey="6LcinMkfAAAAAPP4RoTntihpGIlqmMi6hewnLb3T"></div></p>
            <p><input type="submit" name="btnIngresar" value="Ingresar"></p>
            

        </form>
        <form class="login-container" method="post">
            <p><input type="submit" name="btnRegistrar" value="Registrar"></p>
        </form>
    </div>
    <!-- partial -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js" integrity="sha512-egJ/Y+22P9NQ9aIyVCh0VCOsfydyn8eNmqBy+y2CnJG+fpRIxXMS6jbWP8tVKp0jp+NO5n8WtMUAnNnGoJKi4w==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
</body>

</html>