<?php
//PARA CONECTAR A LA APLICACION.
header("Access-Control-Allow-Origin:*");
header("Access-Control-Allow-Methods:GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Authorization");

//FIN PARA CONECTAR A LA APLICACION.



require_once "connection.php";
require_once "jwt.php";

if($_SERVER["REQUEST_METHOD"]=="OPTIONS") exit();
$jwt = apache_request_headers()["Authorization"];
if(strstr($jwt, "Bearer")) $jwt = substr($jwt, 7);
if(JWT::verify($jwt, "12345678")){
    header(("HTTP/1.1 401 Unauthorized"));
    exit();
}
//DEVUELVE CUALQUIER METODO QUE LE ESTEMOS INDICANDO 
$metodo = $_SERVER["REQUEST_METHOD"];
//FIN DE DEVUELVE CUALQUIER METODO QUE LE ESTEMOS INDICANDO 



switch($metodo){
    //consulta
    case "GET":
        $conexion = connection();
        $conexion->exec("use iot");
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

     //   if($comando -> rowCount()==0) {
    //  header("HTTP/1.1 400 Bad request ");
    //    return;
    //}

    echo json_encode(["status"=> "ok", "id"=> $conexion ->lastInsertId()]);

    //FIN DE Insertar
        break;


        
        case "PUT":
        //ACTUALIZAR
        if (!isset($_GET['type'])|| !isset($_GET['value'])|| !isset($_GET['id'])){
            header("HTTP/1.1 400 Bad Request");
            return;
        }

     $conexion = connection();
     $comando = $conexion -> prepare("UPDATE sensers SET type=:tipo, value=:valor WHERE id=id");
     $comando -> bindValue(":id", $_GET["id"]);
     $comando -> bindValue(":tipo", $_GET['type']);
     $comando -> bindValue(":valor", $_GET['value']);
     $comando -> execute();
        
     echo json_encode(["status"=> "ok"]);


        break;
        case "DELETE":
            //ELIMINAR
            if (!isset($_GET['id'])){
                header("HTTP/1.1 400 Bad Request");
                return;
            }
    
         $conexion = connection();
         $comando = $conexion -> prepare("DELETE FROM sensers WHERE id=:id");
         $comando -> bindValue(":id", $_GET["id"]);
         $comando -> execute();
         echo json_encode(["status"=> "ok"]);
            break;

}