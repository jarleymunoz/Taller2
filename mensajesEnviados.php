<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Mensajes enviados</title>
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
    $idUsuarioActual = $_SESSION['usuario']['id'];
    $query = $conn->prepare("SELECT u.usuario, u.foto, m.texto, m.archivo, m.fecha 
    FROM mensaje m JOIN usuario u ON m.id_destino = u.id_usuario WHERE m.id_remite = :id_remite ORDER BY fecha DESC");
    $res = $query->execute([
        'id_remite'=>$idUsuarioActual
    ]);
    if ($res == true) {
        $mensajes = $query->fetchAll(PDO::FETCH_OBJ);
        $existeMensaje = true;
    }
        if ($existeMensaje) {
        foreach ($mensajes as $data) {
    ?>
       <!-- partial:index.partial.html -->
            <div class="index">
                <div class="index input">
                    <form method="post">
                        <br>
                        <label name="lblAutor_1">Para: <?php echo $data->usuario; ?> </label>
                        <br>
                        <img name="imgFoto_1" src="<?php echo $data->foto; ?>"  right="100" width="100">
                        <br>
                        <label name="lblTexto_1">Mensaje: <?php echo $data->texto; ?> </label>
                        <br>
                        <label name="lblFecha_1">Fecha:  <?php echo $data->fecha; ?> </label>
                        <br>
                        <a name="lnkAdjunto_1"href=" <?php echo $data->archivo?> "target="_blank">Arch. adjunto  </a>
                    </form>
                </div>
            </div>
            <!-- partial -->
            <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js" integrity="sha512-egJ/Y+22P9NQ9aIyVCh0VCOsfydyn8eNmqBy+y2CnJG+fpRIxXMS6jbWP8tVKp0jp+NO5n8WtMUAnNnGoJKi4w==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
</body>
</html>
<?php
    }
}
?>