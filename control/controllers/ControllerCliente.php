<?php
require_once("../models/Cliente.php");
class ControllerCliente {

    public static function cliente($documento){
        //instanciamos el modelos del modelo Cliente
        $cliente = new Cliente();
        //enviamos la variable documento a la funcion
        $getCliente = $cliente->getAll($documento);
        //validamos el resultado
        if($getCliente != false){
            //creamos arreglos preparando json
            $clienteArray = array("success" => true , "error" =>  false, "data" => []);
             //recorregmos arreglo, para proceder a buscar cada deuda de contrato
            foreach($getCliente as $key){
                $clienteArray['data']['documento']  = $key->documento;
                $clienteArray['data']['nombres']    = $key->nombres;
                $clienteArray['data']['referencia'] = $key->referencia;
                //buscarmos la deuda del contrato del cliente de la clase Cliente
                $clienteArray['data']['deuda']      = $cliente->deudasContrato($key->id_contrato);
            }
            //imprimos json de los resultados
            echo json_encode($clienteArray);
           
        }else{
                $clienteArray = array("success" => true , "error" => false , "data" =>"sin registros"  );
                echo json_encode($clienteArray);
            }
        }
    }

$run = new ControllerCliente();
$run->cliente($params->documento);

?>