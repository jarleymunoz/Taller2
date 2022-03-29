<?php
$server='localhost';
$username='root';
$password='Borginie940.';
$database='secure_db';

try{
    $conn=new PDO("mysql:host=$server;dbname=$database;",$username,$password);
    
}catch(PDOException $e){
die('conexión fallida: '.$e->getMessage());
//Comentario de prueba Ricardo
}

?>