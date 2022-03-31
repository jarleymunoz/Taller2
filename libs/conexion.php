<?php
$server='localhost';
$username='root';
$password='root';//cambio clave
$database='secure_db';

//Comentario de prueba

try{
    $conn=new PDO("mysql:host=$server;dbname=$database;",$username,$password);
    
}catch(PDOException $e){
    echo '<script language="javascript">alert("Conexión a base de datos fallida");</script>';
}

?>