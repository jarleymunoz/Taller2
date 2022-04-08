<?php
$server='localhost';
$username='root';
$password='123456';//cambio clave
$database='N20';

//Comentario de prueba

try{
    $conn=new PDO("mysql:host=$server;dbname=$database;",$username,$password);
    
}catch(PDOException $e){
    echo '<script language="javascript">alert("Conexión a base de datos fallida");</script>';
}

?>