<?php


$hostname_seguro = "localhost";
$database_seguro = "controlmas";
$username_seguro = "LNWW(345";
$password_seguro = "9C9UAaqKecHRZA79";
@$conexion_estable = mysql_connect($hostname_seguro, $username_seguro, $password_seguro) or trigger_error(mysql_error(),E_USER_ERROR); 

mysql_query("SET NAMES 'utf8'");


mysql_select_db($database_seguro, $conexion_estable);
$query_contratoganador = "SELECT 
contratos.id_contrato,
contratos.id_cliente,
contratos.id_servicio,
contratos.id_empresa,
SUM(if(deudas.estado = '1' || deudas.estado = '3', deudas.valor_total, 0)) AS total_debe,
SUM(if(deudas.estado = '1' || deudas.estado = '3', deudas.valor_parcial, 0)) AS total_abonos
from contratos 
left join deudas on deudas.id_contrato = contratos.id_contrato

where contratos.estado = 1 and contratos.id_servicio = $servicio

group by contratos.id_contrato";

$contratoganador = mysql_query($query_contratoganador, $conexion_estable) or die(mysql_error());
$row_contratoganador = mysql_fetch_assoc($contratoganador);

#recorremos el array para almacenar los ganadores
$array_list = array();
foreach ($row_contratoganador as $key) {
    if (($key['total_debe'] - $key['total_abonos']) == 0) {
        $temp = array(
            'id_contrato' => $key['id_contrato'],
            'id_servicio' => $key['id_servicio'],
            'id_cliente' => $key['id_cliente'],
            'id_empresa' => $key['id_empresa']
        );
        array_push($array_list, $temp);
    }
}

var_dump($array_list);

?>