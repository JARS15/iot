<?php

use function PHPSTORM_META\type;

require_once "connection.php";

//DEVUELVE CUALQUIER METODO QUE LE ESTEMOS INDICANDO 
$metodo = $_SERVER["REQUEST_METHOD"];
//FIN DE DEVUELVE CUALQUIER METODO QUE LE ESTEMOS INDICANDO 



switch($metodo){
    //consulta
    case "GET":
        $conexion = connection();
        if(isset($_GET['id'])){
          
            //CONSULTA SI MANDAN UN ID
            $id = $_GET['id'];
            $comando = $conexion -> prepare("SELECT * FROM sensers WHERE id =:pid");
            $comando -> bindValue(":pid",$id);
            $comando -> execute();
            $comando -> setFetchMode(PDO::FETCH_ASSOC);
            $resultado = $comando-> fetch();

                        //FIN CONSULTA SI MANDAN UN ID
        }else{
            //ME DEVIELVE TODOS LOS DATOS DE LA TABLA 
            $comando = $conexion -> prepare("SELECT * FROM sensers");
            $comando -> execute();
            $comando -> setFetchMode(PDO::FETCH_ASSOC);
            $resultado = $comando-> fetchAll();
            //ME DEVIELVE TODOS LOS DATOS DE LA TABLA 
        }

        echo json_encode($resultado);
    // fin consulta
    break;




    //Insertar
    case "POST":
   if(!isset($_POST['type']) || !isset($_POST['value'])){
    header("HTML/1.1 400 Bad request");
    return;

   }
   $conexion = connection();
   $comando = $conexion -> prepare("INSERT INTO sensers(user,type, value, dato)
    VALUES(:usuario, :tipo, :valor, :fecha )");
    $comando -> bindValue(":usuario", "admin");
    $comando -> bindValue(":tipo", $_POST['type']);
    $comando -> bindValue(":valor", $_POST['value']);
    $comando -> bindValue(":fecha", date("Y-m-d H:i:s"));
    $comando -> execute();

   if($comando -> rowCount()==0) {
        header("HTTP/1.1 400 Bad request ");
        return;
    }

    echo json_encode(["status"=> "ok", "id"=> $conexion ->lastInsertId()]);

    //FIN DE Insertar
        break;
        case "PUT":

        //ACTUALIZAR
        break;
        case "DELETE":
            //ELIMINAR
            break;

}