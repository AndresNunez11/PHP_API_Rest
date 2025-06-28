<?php
class conexion
{
    static public function conectar()
    {
        try {
            $link = new PDO("mysql:host=localhost;dbname=api_rest;charset=utf8", "root", "1478Sol#yLuna");
            $link->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $link;
        } catch (PDOException $e) {
            die("Error de conexión: " . $e->getMessage());
            // o: die("Error de conexión"); // en producción
        }
    }
}
?>