<?php
/**
 * Función que retorna la cadena de conexión a la base de datos.
 */
function conexion()
{
    $server = 'localhost';
    $username = 'root';
    $password = '123456'; //cambio clave
    $database = 'N20';

    try {
        $con = new PDO("mysql:host=$server;dbname=$database;", $username, $password);
        return $con;
    } catch (PDOException $e) {
        echo "Conexión a base de datos fallida";
    }
}
