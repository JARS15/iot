<?php

require_once "connection.php";
require_once "jwt.php";


if(isset($_REQUEST['users']) && isset($_REQUEST['pass'])){
    $u = $_REQUEST['users'];
    $p = $_REQUEST['pass'];
    $conexion = connection();
    //$comando = $conexion ->prepare("SELECT * FROM users WHERE users='$u'AND pass='$p'");
    $comando = $conexion ->prepare("SELECT users,role FROM users WHERE users=:u AND pass=:p");
    $comando->bindValue(":u", $u);
    $comando->bindValue(":p", md5($p));
    $comando->execute();
    $comando -> setFetchMode(PDO:: FETCH_ASSOC);
    $resultado = $comando -> fetch();
    
    if($resultado){
        $resultado=[ 
            "status"=>"ok",
            "jwt" => JWT::create($resultado, "12345678")
        ];
    }else{
        $resultado = ["status"=>"error"];
    }
    echo json_encode($resultado);
}else{
    header(("HTTP/1.1 400 Bad Request"));
    }

