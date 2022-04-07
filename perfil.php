<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Perfil</title>
    <link rel="stylesheet" href="libs/style.css">

</head>

<body>
    <?php
    require "encabezado.php";
    if (!isset($_SESSION['usuario'])) {
        header("Location: index.php");
    }

    $idUsuarioActual = $_SESSION['usuario']['id'];    

    $query = $conn->prepare("SELECT nombre, apellido, correo, direccion, num_hijos, estado_civil 
                               FROM usuario WHERE id_usuario=:id_usuario ");
    $res = $query->execute([
        'id_usuario' => $idUsuarioActual
    ]);
    if ($res == true) {
        $usuario = $query->fetchAll(PDO::FETCH_OBJ);
    }

    if (isset($_POST['btnCambio'])) {
        header("Location: cambioClave.php");
    }

    if (isset($_POST['btnVolver'])) {
        header("Location: inicio.php");
    }
    if (isset($_POST["btnActualizar"])) {

        if (isset($_POST['anticsrf']) && isset($_SESSION['anticsrf']) && $_SESSION['anticsrf'] == $_POST['anticsrf']) {

            //asigno a nombre
            if (validarTexto($_POST['txtNombre']) == true) {
                $nombre = Limpieza($_POST["txtNombre"]);
            } else {
                echo '<script language="javascript">alert("Nombre inválido");</script>';
                $nombre = "";
            }
            //asigno a apellido
            if (validarTexto($_POST['txtApellidos']) == true) {
                $apellido = Limpieza($_POST["txtApellidos"]);
            } else {
                echo '<script language="javascript">alert("apellido inválido");</script>';
                $apellido = "";
            }
            //asigno correo
            if (validarCorreo($_POST['txtCorreo']) == true) {
                $correo = Limpieza($_POST["txtCorreo"]);
            } else {
                echo '<script language="javascript">alert("correo inválido");</script>';
                $correo = "";
            }
            //asigno dirección

            if ($_POST['txtDir'] == "") {
                echo '<script language="javascript">alert("dirección inválida");</script>';
                $direccion = "";
            } else {
                $direccion = Limpieza($_POST["txtDir"]);
            }
            //asigno cantidad de hijos
            if (is_numeric($_POST['txtNumHij'])) {
                $hijos = Limpieza($_POST["txtNumHij"]);
            } else {
                echo '<script language="javascript">alert("Número de hijos inválido");</script>';
                $hijos = "";
            }
            //asigno a estado civil
            $estados = ['Soltero', 'Casado', 'Otro'];
            if (in_array($_POST['txtEstCivil'], $estados)) {
                $estadoCivil = Limpieza($_POST["txtEstCivil"]);
            } else {
                echo '<script language="javascript">alert("estado civil inválido");</script>';
                $estadoCivil = "";
            }
            //asigno foto
            if (isset($_FILES['fulFoto']) && $_FILES['fulFoto']['error'] === UPLOAD_ERR_OK) {
                $fileTmpPath = $_FILES['fulFoto']['tmp_name'];
                $fileName = $_FILES['fulFoto']['name'];
                $fileNameCmps = explode(".", $fileName);
                $fileExtension = strtolower(end($fileNameCmps));
                $newFileName = md5(time() . $fileName) . '.' . $fileExtension;
                $allowedfileExtensions = array('jpg', 'jpeg');
                if (in_array($fileExtension, $allowedfileExtensions)) {
                    // directory in which the uploaded file will be moved
                    $uploadFileDir = './img/';
                    $dest_path = $uploadFileDir . $newFileName;
                    if (move_uploaded_file($fileTmpPath, $dest_path)) {
                        $img = imagecreatefromjpeg($dest_path);
                        imagejpeg($img, $dest_path, 100);
                        imagedestroy($img);
                    }
                } else {
                    echo '<script language="javascript">alert("Imagen no válida");</script>';
                }
            }

            if ($nombre != "" && $apellido != "" && $correo != "" && $direccion != "" && $hijos != "" && $estadoCivil != "") {
                if (!empty($dest_path)) {
                    $query1 = $conn->prepare("UPDATE  usuario SET 
                                              nombre=:nombre, 
                                              apellido=:apellido, 
                                              correo=:correo, 
                                              direccion=:direccion, 
                                              num_hijos=:num_hijos, 
                                              estado_civil=:estado_civil,
                                              foto=:foto 
                                              WHERE id_usuario=:id_usuario");
                    $res1 = $query1->execute([
                        'nombre' => $nombre,
                        'apellido' => $apellido,
                        'correo' => $correo,
                        'direccion' => $direccion,
                        'num_hijos' => $hijos,
                        'estado_civil' => $estadoCivil,
                        'foto' => $dest_path,
                        'id_usuario' => $idUsuarioActual
                    ]);
                    if ($res1 == true) {
                        
                        header("Location: perfil.php");
                    }
                } else {
                    $query2 = $conn->prepare("UPDATE  usuario  SET 
                                              nombre=:nombre, 
                                              apellido=:apellido, 
                                              correo=:correo,
                                              direccion=:direccion,
                                              num_hijos=:num_hijos,
                                              estado_civil=:estado_civil 
                                              WHERE id_usuario=:id_usuario");
                    $res2 = $query2->execute([
                        'nombre' => $nombre,
                        'apellido' => $apellido,
                        'correo' => $correo,
                        'direccion' => $direccion,
                        'num_hijos' => $hijos,
                        'estado_civil' => $estadoCivil,
                        'id_usuario' => $idUsuarioActual
                    ]);
                    if ($res2 == true) {
                        
                        header("Location: perfil.php");
                    }
                }
            } else {
                echo '<script language="javascript">alert("Datos faltantes");</script>';
            }
        } else {
            echo '<script language="javascript">alert("Petición inválida");</script>';
        }
    }

    anticsrf();

    foreach ($usuario as $data) {
    ?>

        <!-- partial:index.partial.html -->
        <div class="login">

            <h2 class="register-header">Perfil</h2>

            <form class="register-container" method="post" enctype="multipart/form-data">
                <p><input type="hidden" id="anticsrf" name="anticsrf" value="<?php echo $_SESSION['anticsrf'] ?>"></p>
                <p><input type="text" placeholder="Nombre" id="txtNombre" name="txtNombre" value="<?php echo $data->nombre ?>"></p>
                <p><input type="text" placeholder="Apellidos" id="txtApellidos" name="txtApellidos" value="<?php echo $data->apellido ?>"></p>
                <p><input type="text" placeholder="Correo" id="txtCorreo" name="txtCorreo" value="<?php echo $data->correo ?>"></p>
                <p><input type="text" placeholder="Dirección" id="txtDir" name="txtDir" value="<?php echo $data->direccion ?>"></p>
                <p><input type="text" placeholder="Número Hijos" id="txtNumHij" name="txtNumHij" value="<?php echo $data->num_hijos ?>"></p>
                <p><select id="txtEstCivil" name="txtEstCivil" value="<?php echo $data->estado_civil ?>">
                        <option value="Soltero">Soltero</option>
                        <option value="Casado">Casado</option>
                        <option value="Otro">Otro</option>
                    </select></p>
                <p>Foto de perfil (jpg,jpeg)
                    <input type="file" name="fulFoto" id="fulFoto">
                </p>
                <p><input type="submit" value="Actualizar" name="btnActualizar"></p>
                <p><input type="submit" value="Cambiar clave" name="btnCambio"></p>
                <p><input type="submit" value="Volver" name="btnVolver"></p>
            </form>
        </div>
        <!-- partial -->
        <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>

</body>

</html>
<?php
    }
?>