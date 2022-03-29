<?php
$server='localhost';
$username='root';
$password='';//cambio clave
$database='secure_db';

//Comentario de prueba

try{
    $conn=new PDO("mysql:host=$server;dbname=$database;",$username,$password);
    
}catch(PDOException $e){
die('conexión fallida: '.$e->getMessage());

}

?>