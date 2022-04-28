<?php
require_once "../libs/conexion.php";
require_once "../libs/tools.php";

/**
 *Función que retorna toda la lista de articulos de un usuario puntual
 *@param id: Id del usuario. 
 */
function misArticulos($id)
{
    $con = conexion();

    $query = $con->prepare("SELECT id_articulo, id_usuario, texto, publico, fecha_publi 
                            FROM articulo 
                            WHERE id_usuario =:id_usuario 
                            order by fecha_publi desc");
    $res = $query->execute([
        'id_usuario' => $id
    ]);
    if ($res == true) {
        $query->setFetchMode(PDO::FETCH_ASSOC);
        return $query->fetchAll();
    }
}
/**
 * Función que valida en la base de datos si existe el usuario y clave
 * @param usuario: nombre del usuario.
 * @param clave:   Clave del usuario.
 */
function validaClave($usuario, $clave)
{
    $con = conexion();
    $query = $con->prepare("SELECT usuario,clave 
                            FROM usuario 
                            WHERE usuario =:usuario");
    $res = $query->execute([
        'usuario' => $usuario
    ]);
    if ($res == true) {
        $usuar = $query->fetchAll(PDO::FETCH_OBJ);
        if (!empty($usuar)) {
            foreach ($usuar as $data) {
                $user = $data->usuario;
                $pass = $data->clave;
            }

            if (password_verify($clave, $pass)) {

                return true;
            } else {

                return false;
            }
        } else {

            return false;
        }
    }
}
/**
 * Función que valida si existe un nombre de usuario en la base de datos
 * @param: usuario: Nombre de usuario a buscar
 */
function buscarUsuario($usuario)
{
    $con = conexion();
    $query = $con->prepare("SELECT usuario
                            FROM usuario
                            WHERE usuario =:usuario 
                            ");
    $res = $query->execute([
        'usuario' => $usuario
    ]);
    if ($res == true) {
        $query->setFetchMode(PDO::FETCH_ASSOC);
        return $query->fetchAll();
    }
}
/**
 * Función que retorna el id de usuario en la base de datos
 * @param: usuario: Nombre de usuario a buscar
 */
function buscarIdUsuario($usuario)
{
    $con = conexion();
    $query = $con->prepare("SELECT id_usuario
                            FROM usuario
                            WHERE usuario =:usuario 
                            ");
    $res = $query->execute([
        'usuario' => $usuario
    ]);
    if ($res == true) {
        $usuar = $query->fetchAll(PDO::FETCH_OBJ);
        foreach ($usuar as $data) {
            $id = $data->id_usuario;
            return $id;
        }
    }
}

/**
 * Funcion que realiza el registro de un usuario
 * @param:nombre:      Nombre del usuario 
 * @param:apellido:    Apellido del usuario 
 * @param:correo:      Correo del usuario
 * @param:direccion:   Dirección del usuario
 * @param:hijos:       Número de hijos
 * @param:estadocivil: Estado civil
 * @param:rutahas:     Ruta de la foto
 * @param:usuaro:      Nombre de usuario
 * @param:clavehas:    Clave de usuario
 */
function registroUsuario($nombre, $apellido, $correo, $direccion, $hijos, $estadocivil, $rutahas, $usuario,  $clavehas)
{
    $conn = conexion();
    $query1 = $conn->prepare("INSERT INTO usuario ( nombre, 
                                                    apellido,
                                                    correo,
                                                    direccion,
                                                    num_hijos,
                                                    estado_civil,
                                                    foto,
                                                    usuario,clave) 
                            VALUES(:nombre,
                                    :apellido,
                                    :correo,
                                    :direccion,
                                    :num_hijos,
                                    :estado_civil,
                                    :foto,
                                    :usuario,
                                    :clave)");
    $res1 = $query1->execute([
        'nombre' => $nombre,
        'apellido' => $apellido,
        'correo' => $correo,
        'direccion' => $direccion,
        'num_hijos' => $hijos,
        'estado_civil' => $estadocivil,
        'foto' => $rutahas,
        'usuario' => $usuario,
        'clave' => $clavehas
    ]);
    if ($res1 == true) {
        return true;
    } else {
        return false;
    }
}
/**
 * Función para crear un articulo
 * @param: usuario: Usuario actual autenticado
 * @param: texto:   Contenido del artículo a publicar
 * @param: publico  SI para hacerlo público NO para hacerlo no público
 */
function crearArticulo($usuario, $texto, $publico)
{
    $conn = conexion();
    $idUsuario = buscarIdUsuario($usuario);

    if ($publico == 'SI') {
        $query = $conn->prepare("INSERT INTO articulo (id_usuario, texto, publico) 
                                 VALUES(:id_usuario,:texto,:publico)");
        $res = $query->execute([
            'id_usuario' => $idUsuario,
            'texto' => $texto,
            'publico' => 'SI'
        ]);
        if ($res == true) {
            return true;
        } else {
            return false;
        }
    } else {
        $query = $conn->prepare("INSERT INTO articulo (id_usuario, texto, publico) 
                                 VALUES(:id_usuario,:texto,:publico)");
        $res = $query->execute([
            'id_usuario' => $idUsuario,
            'texto' => $texto,
            'publico' => 'NO'
        ]);
        if ($res == true) {
            return true;
        } else {
            return false;
        }
    }
}
/**
 * Función para verificar el estado de un artículo
 * @param: id_articulo
 */
function estadoArticulo($id_articulo)
{
    $con = conexion();
    $query = $con->prepare("SELECT publico
                            FROM articulo
                            WHERE id_articulo =:id_articulo 
                            ");
    $res = $query->execute([
        'id_articulo' => $id_articulo
    ]);
    if ($res == true) {
        $articulo = $query->fetchAll(PDO::FETCH_OBJ);
        foreach ($articulo as $data) {
            $publico = $data->publico;
            return $publico;
        }
    }
}
/**
 * Función para publicar o despublicar un artículo
 * @param: id_articulo: id del artículo a modificar
 * @param: publico:     SI para publicar NO para despublicar
 */
function actualizarEstadoArticulo($id_articulo, $publico)
{
    $conn = conexion();
    $estadoArticulo = estadoArticulo($id_articulo); //Busco el estado actual del articulo

    if ($publico == 'SI' && $estadoArticulo == 'NO') {
        $query = $conn->prepare("UPDATE articulo 
        SET publico= 'SI' 
        WHERE id_articulo=:id_articulo");
        $res = $query->execute([
            'id_articulo' => $id_articulo
        ]);
        if ($res == true) {
            return true;
        }
    } elseif ($publico == 'NO' && $estadoArticulo == 'SI') {
        $query = $conn->prepare("UPDATE articulo 
        SET publico= 'NO' 
        WHERE id_articulo=:id_articulo");
        $res = $query->execute([
            'id_articulo' => $id_articulo
        ]);
        if ($res == true) {
            return true;
        }
    } elseif ($estadoArticulo == 'SI' && $publico == 'SI') {
        echo 'El artículo ya está público';
        return false;
    } else {
    }
    if ($estadoArticulo == 'NO' && $publico == 'NO') {
        echo 'El artículo ya está NO público';
        return false;
    }
}
/**
 * Función que retorna el id de un artículo en la base de datos
 * @param: id_articulo: Nombre de usuario a buscar
 */
function buscarIdArticulo($id_articulo)
{
    $con = conexion();
    $query = $con->prepare("SELECT id_articulo
                            FROM articulo
                            WHERE id_articulo =:id_articulo 
                            ");
    $res = $query->execute([
        'id_articulo' => $id_articulo
    ]);
    if ($res == true) {
        $articulo = $query->fetchAll(PDO::FETCH_OBJ);
        foreach ($articulo as $data) {
            $id = $data->id_articulo;
            return $id;
        }
    }
}
/**
 * Función que elimina un artículo
 * @param: id_articulo:  Id del artículo a eliminar
 */
function eliminarArticulo($id_articulo)
{
    $conn = conexion();
    if (buscarIdArticulo($id_articulo)) {
        $query = $conn->prepare("DELETE FROM articulo 
                                 WHERE id_articulo=:id_articulo");
        $res = $query->execute([
            'id_articulo' => $id_articulo
        ]);
        if ($res == true) {
            return true;
        } else {
            return false;
        }
    } else {
        echo 'Artículo no existe';
        return false;
    }
}
/**
 * Funcion para enviar un mensaje a otro destinatario
 * @param: remitente: Usuario que envía el mensaje
 * @param: destinatario: Usuario al que envía el mensaje
 * @param: mensaje: Cuerpo del mensaje
 * @param: adjunto: Archivo a enviar
 * 
 */
function enviarMensaje($remitente, $destinatario, $mensaje, $adjunto)
{
    $idRemite = buscarIdUsuario($remitente);
    $idDestino = buscarIdUsuario($destinatario);
    $conn = conexion();
    $query = $conn->prepare("INSERT INTO mensaje(id_remite,id_destino,texto,archivo) 
                             VALUES (:id_remite,:id_destino,:texto,:archivo)");
    $res = $query->execute([
        'id_remite' => $idRemite,
        'id_destino' => $idDestino,
        'texto' => $mensaje,
        'archivo' => $adjunto
    ]);
    if ($res == true) {
        return true;
    } else {
        return false;
    }
}
/**
 * Función para actualizar datos del usuario
 * @param: campoCambiar: campo de la tabla usuario que se modifica.
 * @param: valor:        valor de a inserar
 * @param: usuario:      Usuario para modificar 
 */
function actualizarUsuario($campo, $valor, $usuario)
{
    $conn = conexion();
    $query = $conn->prepare("UPDATE usuario set $campo=:valor
                             WHERE usuario=:usuario");
    $res = $query->execute([
        'valor' => $valor,
        'usuario' => $usuario,
        
    ]);
    if ($res == true) {
        return true;
    } else {
        return false;
    }                         
}
/**
 * Función que actualiza la clave
 * @param: claveAnterior: clave actual del usuario
 * @param: claveNueva:    clave nueva para actualizar 
 * @param: claveRepetir:  clave nueva para confirmar
 * @param: usuario:       Usuario actual para el cambio   
 */
function actualizaClave($claveAnterior,$claveNueva,$claveRepetir,$usuario){
    $conn = conexion(); 
    $id_usuario=buscarIdUsuario($usuario);
    //busco la clave actual
    $query = $conn->prepare("SELECT clave 
                             FROM usuario 
                             WHERE id_usuario=:id_usuario ");
    $res = $query->execute([
        'id_usuario' => $id_usuario
    ]);

    if ($res == true) {
        $usua = $query->fetchAll(PDO::FETCH_OBJ);
    }

    foreach ($usua as $data) {
        if (password_verify($claveAnterior, $data->clave)) {
            if ($claveNueva == $claveRepetir) {
                $query1 = $conn->prepare("UPDATE usuario 
                                          SET clave=:clave 
                                          WHERE id_usuario=:id_usuario");
                $claveNuevaHash =  password_hash($claveNueva, PASSWORD_DEFAULT);
                $res1 = $query1->execute([
                    'clave' => $claveNuevaHash,
                    'id_usuario' => $id_usuario
                ]);
                if ($res1 == true) {
                    return true;
                }
            } else {
                echo 'Las claves no coinciden';
                return false;
            }
        } else {
            echo 'Clave anterior no coincide';
            return false;
        }
    }

}
