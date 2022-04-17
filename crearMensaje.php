<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Crear mensaje</title>
    <link rel="stylesheet" href="libs/style.css">

</head>

<body>
    <?php
    require "encabezado.php";
    require "encabezadoMensajes.php";
    LimpiezaKV();

    if (!isset($_SESSION['usuario'])) {
        header("Location: index.php");
    }
    $dest_path = '';
    $idUsuarioActual = $_SESSION['usuario']['id'];
    //query para extraer los usuarios para enviar el mensaje, no muestra el actual
    $query = $conn->prepare("SELECT id_usuario, usuario 
                             FROM usuario 
                             WHERE id_usuario!=:id_usuario");
    $res = $query->execute([
        'id_usuario' => $idUsuarioActual
    ]);
    if ($res == true) {
        $usuarios = $query->fetchAll(PDO::FETCH_OBJ);
    }
    //Botón de enviar
    if (isset($_POST['btnEnviar'])) {
        //anticsrf evitar envío de peticiones cruzadas
        if (isset($_POST['anticsrf']) && isset($_SESSION['anticsrf']) && $_SESSION['anticsrf'] == $_POST['anticsrf']) {
            //Falta crear función que limpie el texto

            if (is_numeric($_POST['cmbDestino']) && ($_POST['txtMensaje'])) {
                //asigo el archivo cargado
                if (isset($_FILES['fulAdjunto']) && $_FILES['fulAdjunto']['error'] === UPLOAD_ERR_OK) {
                    $fileTmpPath = $_FILES['fulAdjunto']['tmp_name'];
                    $fileName = $_FILES['fulAdjunto']['name'];
                    $fileNameCmps = explode(".", $fileName);
                    $fileExtension = strtolower(end($fileNameCmps));
                    $newFileName = md5(time() . $fileName) . '.' . $fileExtension;
                    $allowedfileExtensions = array('jpg', 'gif', 'png', 'jpeg', 'pdf', 'docx', 'xlsx', 'pptx');
                    if (in_array($fileExtension, $allowedfileExtensions)) {
                        // directory in which the uploaded file will be moved
                        $uploadFileDir = './archivos/';
                        $dest_path = $uploadFileDir . $newFileName;
                        move_uploaded_file($fileTmpPath, $dest_path);

                        //guardo el destinatario    
                $destinatario = Limpieza($_POST['cmbDestino']);
                if (validarMensaje($_POST['txtMensaje']) == true) {
                    //guardo el mensaje
                    $mensaje = Limpieza($_POST['txtMensaje']);
                    //query para insertar el mensaje
                    $query1 = $conn->prepare("INSERT INTO mensaje(id_remite,id_destino,texto,archivo) 
                                          VALUES (:id_remite,:id_destino,:texto,:archivo)");
                    $res1 = $query1->execute([
                        'id_remite' => $idUsuarioActual,
                        'id_destino' => $destinatario,
                        'texto' => $mensaje,
                        'archivo' => $dest_path
                    ]);
                    if ($res1 == true) {

                        notificaciones('Mensaje enviado');
                        header("refresh:2;url=crearMensaje.php");
                    } else {
                        notificaciones('Error de envío');
                        header("refresh:2;url=crearMensaje.php");
                    }
                } else {
                    notificaciones('Mensaje inválido');
                }
                    }else {
                        notificaciones('Adjunto inválido');
                    }
                }
                
            } else {
                notificaciones('Usuario no existente o mensaje vacío');
                header("refresh:2;url=crearMensaje.php");
            }
        } else {
            notificaciones('Petición inválida');
            header("refresh:2;url=crearMensaje.php");
        }
        anticsrf();
    }
    ?>
    <!-- partial:index.partial.html -->
    <div class="login">
        <div class="login-triangle"></div>

        <h2 class="login-header">Crear mensaje</h2>

        <form class="login-container" method="post" enctype="multipart/form-data">
            <p><input type="hidden" id="anticsrf" name="anticsrf" value="<?php echo $_SESSION['anticsrf'] ?>"></p>
            <p><label> Destinatario:</label>
                <select name="cmbDestino">
                    <?php foreach ($usuarios as $data) {
                    ?>
                        <option value="<?php echo $data->id_usuario; ?>"><?php echo $data->usuario ?></option>
                    <?php
                    }
                    ?>
                </select>
            </p>

            <p><textarea name="txtMensaje" id="txtMensaje" placeholder="Escribir mensaje" rows="20" cols="41" maxlength="140"></textarea></p>
            <p><label>Arch. Adjunto:</label>
                <input type="file" name="fulAdjunto">
            </p>
            <p><input type="submit" name="btnEnviar" value="Crear mensaje"></p>
        </form>
    </div>
    <!-- partial -->
    <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>
</body>

</html>