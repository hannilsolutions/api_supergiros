<?php
require_once("../models/Cliente.php");
class ControllerCliente {

    public  function cliente($documento){
        //instanciamos el modelos del modelo Cliente
        $cliente = new Cliente();
        //enviamos la variable documento a la funcion
        $getCliente = $cliente->getAll($documento);
        //validamos el resultado
        if($getCliente != false){
            //creamos arreglos preparando json
            $clienteArray = array("success" => true , "error" =>  false, "data" => []);
 
            
            //se arma la cabecera del json documento y nombre
            $clienteArray['data']['documento']  = $getCliente->documento;
            $clienteArray['data']['nombres']    = $getCliente->nombres;
            //
            //realizamos la consulta de la cantidad de contratos
            $getCount = $cliente->countContrato($getCliente->id_cliente);
            
            //en caso que de que solo sea un contrato no requiere de foreach
            if($getCount->valor == 1 )
            {
                //realiza la consulta del contrato, enviando el parametro de id_cliente y cantidad de contratos
                $getContrato    = $cliente->contratos($getCliente->id_cliente , $getCount->valor );
                //al ser un solo contrato, se realiza la busqueda de las deudas de dicho contrato $getContrato->id_contrato
                $getDeudas      = $cliente->findByDeudas($getContrato->id_contrato);
                //creamos un array temporal para almacenar la informacion
                $array_temp = array();
                //al array temporal agregamos la referencia del getDeudas->referencia
                $array_temp["referencia"] = $getDeudas->referencia;
                //al array temporal agregamos el valor, restando del valor total - el abono
                $array_temp["valor"]    = $getDeudas->total_debe - $getDeudas->total_abonos;
                //se agrega la informacion al arraygeneral en deudas
                $clienteArray["data"]["deudas"] = $array_temp;
                //imprimo el json del array principal
                echo json_encode($clienteArray);
            }elseif($getCount->valor > 1 )
            {
                //en caso que sean mas de un contrato debemos enviar el getCliente-id_cliente y el valor 2
                $getContrato    = $cliente->contratos($getCliente->id_cliente , $getCount->valor );
                //creacion de array temporal
                $deudasArrayTemp = array();
                //creacion de segundo array temporal para almacenar los registros
                $deudasArray = array();
                //para mas de 1 registro realizamos un foreach con la consulta $getContrato
                foreach($getContrato as $key){
                    //recorremos cada contrato obtenido buscando la deuda de cada uno
                    $getDeudas      = $cliente->findByDeudas($key->id_contrato);
                    //almanceamos la informacion en el primer array temporal la referencoa
                    $deudasArrayTemp["referencia"]  = $getDeudas->referencia;
                    //almacenamos la informacion de el primer array temporal de la resta de los valores
                    $deudasArrayTemp["valor"]       = $getDeudas->total_debe - $getDeudas->total_abonos;
                    //adicionar el primer arreglo al segundo temporal
                    array_push($deudasArray , $deudasArrayTemp);
                }
                //al array principal se agrega el arreglo temporal
                $clienteArray["data"]["deudas"] = $deudasArray;
                //imprimimos en json el array principal
                echo json_encode($clienteArray);

            }else{
                //cero
                //en caso que el count de contrato es igual a 0
                $clienteArray["data"]["deudas"] = "sin contratos";
                //imprimo el json del array principal
                echo json_encode($clienteArray);

            }
           
           
        }else{
                $clienteArray = array("success" => true , "error" => false , "data" =>"sin registros"  );
                echo json_encode($clienteArray);
            }
        }
 
    }

$run = new ControllerCliente();
$run->cliente($_POST['documento']);

?>