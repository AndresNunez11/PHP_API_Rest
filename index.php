<?php

include_once "Controladores/rutasControlador.php";
include_once "Controladores/cursosControlador.php";
include_once "Controladores/clientesControlador.php";
include_once "Modelos/cursosModel.php";
include_once "Modelos/clientesModel.php";

$rutas = new RutasControlador();
$rutas->inicio();





?>