<?php  
header("Cache-Control: no-cache, must-revalidate");  
header("HTTP/1.1 200 OK");
header("Content-type:application/json; charset=utf-8");
header("Content-autor:Hannil Solutions");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Origin: *");
header("Allow: POST");
 
#$request    = file_get_contents("php://input");
#$params     = json_decode($request);
$api_key    =   'f24f0aaa81db035965e65f60c5e54c41';
$key       =   $_POST['key'];
 
 

if($api_key != $key)
{ 
    $respuesta = array("sucess"=> true, "Error" => "KEY INVALIDO" , "key" => $key);
    echo json_encode($respuesta);
}
else
{
/**parametro m 1 para consultar las deudas de una cliente por documento
	creación para consultar con la alianza supergiros
*/ 
if($_POST['m']  ==  1) {include('../rutas/rutas.php');}  


/**
Paramento m 2 para consultar los pagos del dia
*/
if($_POST['m'] == 2 ){ include('../rutas/pagosdia.php');}
if($_POST['m'] == 3 ){ include('../rutas/pagosdia.php');}
}
 

 ?>