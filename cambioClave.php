<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Cambio clave</title>
    <link rel="stylesheet" href="libs/style.css">

</head>

<body>
    <?php
    require 'encabezado.php';
    if (!isset($_SESSION['usuario'])) {
        header("Location: index.php");
    }
    $idUsuarioActual = $_SESSION['usuario']['id'];

    $query = $conn->prepare("SELECT clave 
                               FROM usuario 
                               WHERE id_usuario=:id_usuario ");
    $res = $query->execute([
        'id_usuario' => $idUsuarioActual
    ]);

    if ($res == true) {
        $usuario = $query->fetchAll(PDO::FETCH_OBJ);
    }

    if (isset($_POST["btnActualizar"])) {

        if (isset($_POST['anticsrf']) && isset($_SESSION['anticsrf']) && $_SESSION['anticsrf'] == $_POST['anticsrf']) {

            if (validarClave($_POST["txtAnterior"]) == true && validarClave($_POST["txtNueva"]) == true && validarClave($_POST["txtRepetir"]) == true) {
                $claveAntigua = Limpieza($_POST["txtAnterior"]);
                $claveNueva = Limpieza($_POST["txtNueva"]);
                $claveRepetir = Limpieza($_POST["txtRepetir"]);

                foreach ($usuario as $data) {
                    if (password_verify($claveAntigua, $data->clave)) {
                        if ($claveNueva == $claveRepetir) {
                            $query1 = $conn->prepare("UPDATE usuario 
                                                    SET clave=:clave 
                                                    WHERE id_usuario=:id_usuario");
                            $claveNuevaHash =  password_hash($claveNueva, PASSWORD_DEFAULT);
                            $res1 = $query1->execute([
                                'clave' => $claveNuevaHash,
                                'id_usuario' => $idUsuarioActual
                            ]);
                            if ($res1 == true) {
                                echo '<script language="javascript">alert("Clave actualizada");</script>';
                                cerrarSesion();
                            }
                        } else {
                            echo '<script language="javascript">alert("Claves no coinciden");</script>';
                        }
                    } else {
                        echo '<script language="javascript">alert("Clave actual incorrecta");</script>';
                    }
                }
            } else {
                echo '<script language="javascript">alert("Claves inválidas");</script>';
            }
        } else {
            echo '<script language="javascript">alert("Petición inválida");</script>';
        }
    }
    anticsrf();
    ?>
    <!-- partial:index.partial.html -->
    <div class="login">
        <div class="login-triangle"></div>

        <h2 class="login-header">Cambio clave</h2>

        <form class="login-container" method="post">
            <p><input type="hidden" id="anticsrf" name="anticsrf" value="<?php echo $_SESSION['anticsrf'] ?>"></p>
            <p><input type="password" name="txtAnterior" id="txtAnterior" placeholder="Clave actual" required="required"></p>
            <p><input type="password" name="txtNueva" id="txtNueva" placeholder="Nueva clave" required="required"></p>
            <p><input type="password" name="txtRepetir" id="txtRepetir" placeholder="Repetir clave" required="required"></p>
            <p><input type="submit" name="btnActualizar" value="Actualizar"></p>
        </form>
    </div>
    <!-- partial -->
    <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>
</body>

</html>