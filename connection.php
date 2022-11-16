<?php

// CONXION A LA BASE DE ADATOS QUE NOSOTROS ELEGIMOS
require_once "config.php";
function connection(){
    try{
        $c = new PDO ("mysql:host=".Config::HOST.";dbname=".Config::DB,Config:: USER, Config:: PASS);
        $c -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $c;
    }catch(PDOException $e){
        echo $e->getMessage();
        exit($e->getMessage());

    }
}
// FIN CONXION A LA BASE DE ADATOS QUE NOSOTROS ELEGIMOS