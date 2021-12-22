<?php
require_once("../models/Pago.php");
class ControllerPagos {

    public static function pagos($fecha){
        //instanciamos el modelos del modelo Pagos
        $pagos = new Pago();
        //enviamos la variable fecha a la funcion
        $pagos = $pagos->getPagos($fecha);
        //validamos el resultado
        if($pagos != false){
            //creamos arreglos preparando json
            $pagosArray = array("success" => true , "error" =>  false, "data" => []);
             //recorregmos arreglo, para proceder armar la respuesta
            foreach($pagos as $key){
                $pagosBefore['id']  = $key->id;
                $pagosBefore['rc']    = $key->id_recibo_caja;
                $pagosBefore['valor'] = $key->valor_cobro;
                $pagosBefore['celular'] = $key->celular_a;

                array_push($pagosArray['data'], $pagosBefore );
            }

            //imprimos json de los resultados
            echo json_encode($pagosArray);
           
        }else{
                $pagosArray = array("success" => true , "error" => false , "data" =>"sin registros");
                echo json_encode($pagosArray);
            }
        }
/**
    
conteo de registros para validar con api
        **/
        public function countPagos($fecha){
            $pago = new Pago();

            $countPagos = $pago->getCountPago($fecha);

            if($countPagos != false){
                 $pagosArray = array("success" => true , "error" =>  false, "data" => $countPagos);
                 echo json_encode($pagosArray);

            }else{
                $pagosArray = array("success" => true , "error" => false , "data" =>"sin registros");
                echo json_encode($pagosArray);
            }
        }
    }

$run = new ControllerPagos();
if($params->m == 3){ $run->countPagos($params->dia);}
if($params->m == 2){ $run->pagos($params->dia);}

