<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Mis artículos</title>
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
    $idUsuarioActual=$_SESSION['usuario']['id'];
    $query = $conn->prepare("SELECT id_articulo, id_usuario, texto, publico, fecha_publi 
    FROM articulo WHERE id_usuario =:id_usuario order by fecha_publi desc");
    $res = $query->execute([
        'id_usuario'=>$idUsuarioActual
    ]);
    if ($res == true) {
        $articulos = $query->fetchAll(PDO::FETCH_OBJ);
        $existeArticulo = true;
    }
    if (isset($_POST['btnPublicar_1'])) {
        foreach ($articulos as $data) {
            if ($data->id_articulo == $_POST['btnPublicar_1']) {
                if ($data->publico == "NO") {
                    $publicar = $conn->prepare("UPDATE articulo SET publico= 'SI' WHERE id_articulo=:id_articulo");
                    $res1 = $publicar->execute([
                       'id_articulo'=>$data->id_articulo     
                    ]);
                    if ($res1 == true) {
                        echo '<script language="javascript">alert("Artículo publicado");</script>';
                        header("Location: misArticulos.php");
                    }
                } else {
                    echo '<script language="javascript">alert("El artículo está público");</script>';
                }
            }
        }
    }
    if (isset($_POST['btnDespublicar_1'])) {
        foreach ($articulos as $data) {
            if ($data->id_articulo == $_POST['btnDespublicar_1']) {
                if ($data->publico == "SI") {
                    $despublicar = $conn->prepare("UPDATE articulo SET publico= 'NO' WHERE id_articulo= :id_articulo");
                    $res2 = $despublicar->execute([
                    'id_articulo'=>$data->id_articulo 
                ]);
                    if ($res2 == true) {
                        echo '<script language="javascript">alert("Artículo despublicado");</script>';
                        header("Location: misArticulos.php");
                    }
                } else {
                    echo '<script language="javascript">alert("El artículo está no está público");</script>';
                }
            }
        }
    }
    if (isset($_POST['btnBorrar_1'])) {
        foreach ($articulos as $data) {
            if ($data->id_articulo == $_POST['btnBorrar_1']) {
                $delete = $conn->prepare("DELETE FROM articulo WHERE id_articulo=:id_articulo");
                $res3 = $delete->execute([
                    'id_articulo'=>$data->id_articulo
                ]);
                if ($res3 == true) {
                    echo '<script language="javascript">alert("Artículo borrado");</script>';
                    header("Location: misArticulos.php");
                }
            }
        }
    }
    ?>
    <?php
    if ($existeArticulo) {
        foreach ($articulos as $data) {
    ?>
       <!-- partial:index.partial.html -->
            <div class="index">
                <div class="index input">
                    <form method="post">
                        <br>
                        <label name="lblTexto_1">Artículo: <?php echo $data->texto; ?> </label>
                        <br>
                        <label name="lblFecha_1">Fecha publicación: <?php echo $data->fecha_publi; ?> </label>
                        <br>
                        <label name="lblPublico_1">Público: <?php echo $data->publico; ?> </label>
                    </form>
                </div>
            </div>
            <div class="index">
                <div class="index input">
                    <form method="post">
                        <br>
                        <button type="submit" class="login" name="btnPublicar_1" value="<?php echo $data->id_articulo ?>">Publicar </button>
                        <button type="submit" class="login" name="btnDespublicar_1" value="<?php echo $data->id_articulo ?>">Despublicar</button>
                        <button type="submit" class="login" name="btnBorrar_1" value="<?php echo $data->id_articulo ?>">Borrar</button>
                    </form>
                </div>
            </div>
            <!-- partial -->
            <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>
</body>
</html>
<?php
    }
}
?>