<?php  
header("Cache-Control: no-cache, must-revalidate");  
header("HTTP/1.1 200 OK");
header("Content-type:application/json; charset=utf-8");
header("Content-autor:Hannil Solutions");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Origin: *");
header("Allow: POST");
 
$request    = file_get_contents("php://input");
$params     = json_decode($request);
$api_key    =   'f24f0aaa81db035965e65f60c5e54c41';
$key       =   $params->key;
 
 

if($api_key != $key)
{ 
    $respuesta = array("sucess"=> true, "Error" => "KEY INVALIDO" , "key" => $key);
    echo json_encode($respuesta);
}
else
{
 
if($params->m  ==  1) {include('../rutas/rutas.php');}  

}
 

 ?>