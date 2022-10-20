<?php
// TESTEO DE LA BASE DE DATOS 
require_once "connection.php";

$c = connection();


if($c) echo "conectado a base de datos";
// FIN DEL TESTEO DE LA BASE DE DATOS 