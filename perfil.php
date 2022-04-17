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
                notificaciones('Nómbre inválido');
                $nombre = "";
            }
            //asigno a apellido
            if (validarTexto($_POST['txtApellidos']) == true) {
                $apellido = Limpieza($_POST["txtApellidos"]);
            } else {
                notificaciones('Apellido inválido');
                $apellido = "";
            }
            //asigno correo
            if (validarCorreo($_POST['txtCorreo']) == true) {
                $correo = Limpieza($_POST["txtCorreo"]);
            } else {
                notificaciones('Correo inválido');
                $correo = "";
            }
            //asigno dirección

            if ($_POST['txtDir'] == "") {
                notificaciones('Dirección inválida');
                $direccion = "";
            } else {
                $direccion = Limpieza($_POST["txtDir"]);
            }
            //asigno cantidad de hijos
            if (is_numeric($_POST['txtNumHij'])) {
                $hijos = Limpieza($_POST["txtNumHij"]);
            } else {
                notificaciones('Número de hijos inválido');
                $hijos = "";
            }
            //asigno a estado civil
            $estados = ['Soltero', 'Casado', 'Otro'];
            if (in_array($_POST['txtEstCivil'], $estados)) {
                $estadoCivil = Limpieza($_POST["txtEstCivil"]);
            } else {
                notificaciones('Estado civil inválido');
                $estadoCivil = "";
            }
            //asigno foto
            if (isset($_FILES['fulFoto']) && $_FILES['fulFoto']['error'] === UPLOAD_ERR_OK) {
                $fileTmpPath = $_FILES['fulFoto']['tmp_name'];
                $fileName = $_FILES['fulFoto']['name'];
                $fileNameCmps = explode(".", $fileName);
                $fileExtension = strtolower(end($fileNameCmps));
                $newFileName = md5(time() . $fileName) . '.' . $fileExtension;
                $allowedfileExtensions = array('jpg', 'jpeg', 'png', 'gif');
                if (in_array($fileExtension, $allowedfileExtensions)) {
                    // directory in which the uploaded file will be moved
                    $uploadFileDir = './img/';
                    $dest_path = $uploadFileDir . $newFileName;
                    if (move_uploaded_file($fileTmpPath, $dest_path)) {
                        if ($fileExtension == 'jpeg' || $fileExtension == 'jpg')
                        {
                            $img = imagecreatefromjpeg($dest_path);
                            imagejpeg($img, $dest_path, 100);
                            imagedestroy($img);
                        }
                        else if ($fileExtension == 'png')
                        {
                            $img = imagecreatefrompng($dest_path);
                            imagepng($img, $dest_path, 9);
                            imagedestroy($img);
                        }
                        else if ($fileExtension == 'gif')
                        {
                            $img = imagecreatefromgif($dest_path);
                            imagegif($img, $dest_path, 100);
                            imagedestroy($img);
                        }
                    }
                } else {
                    notificaciones('Imagen inválida');
                }
            }

             if ($nombre != "" &&$apellido != "" && $correo != "" && $direccion != "" && $hijos != "" && $estadoCivil != "") {
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

                        notificaciones('Datos actualizados');
                        header("refresh:2;url=inicio.php");
                        
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
                        notificaciones('Datos actualizados');
                        header("refresh:2;url=inicio.php");
                        
                    }
                }
            } else {
                notificaciones('Datos faltantes');
                header("refresh:2;url=perfil.php");
            }
        } else {
            notificaciones('Petición inválida');
            header("refresh:2;url=perfil.php");
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
                <p><input type="text" placeholder="Apellidos" id="txtApellidos" name="txtApellidos" pattern="[A-Za-z]+" required="required" value="<?php echo $data->apellido ?>"></p>
                <p><input type="email" placeholder="Correo" id="txtCorreo" name="txtCorreo" required="required" value="<?php echo $data->correo ?>"></p>
                <p><input type="text" placeholder="Dirección" id="txtDir" name="txtDir" pattern="[A-Za-z0-9'\.\-\s\,]" required="required" value="<?php echo $data->direccion ?>"></p>
                <p><input type="number" placeholder="Número Hijos" id="txtNumHij" name="txtNumHij" pattern="[0-9]+" required="required" value="<?php echo $data->num_hijos ?>"></p>
                <p><select id="txtEstCivil" name="txtEstCivil" required="required" value="<?php echo $data->estado_civil ?>">
                        <option value="Soltero">Soltero</option>
                        <option value="Casado">Casado</option>
                        <option value="Otro">Otro</option>
                    </select></p>
                <p>Foto de perfil (jpg,jpeg,png,gif)
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