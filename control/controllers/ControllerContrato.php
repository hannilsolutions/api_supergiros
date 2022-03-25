<?php
require_once("../models/Contrato.php");
class ControllerContrato {

    public static function findByCus($contrato){
        //instanciamos el modelos del modelo Cliente
        $findByCus = new Contrato();
        //enviamos la variable documento a la funcion
        $getfindByCus = $findByCus->findByCus($contrato);
        //validamos el resultado
        if($getfindByCus != false){
            //creamos arreglos preparando json
            $contratoArray = array("success" => true , "error" =>  false, "data" => []);
             //recorregmos arreglo, para proceder a buscar cada deuda de contrato
            foreach($getfindByCus as $key){
                $contratoArray['data']['id_contrato']  = $key->id_contrato;
                $contratoArray['data']['direccion']    = $key->direccion;
                
            }
            //imprimos json de los resultados
            echo json_encode($contratoArray);
           
        }else{
                $contratoArray = array("success" => true , "error" => false , "data" =>"sin registros"  );
                echo json_encode($contratoArray);
            }
        }

        /*
        Generar información de una factura por numero de factura
        y el id_servicio, tener presente vista creada
        **/
    public function findByFactura($factura, $servicio)
        {
            $cliente = new Contrato();
            $getCliente = $cliente->findByFactura($factura , $servicio);

            if($getCliente != false)
            {
                $resultadoFact  = unserialize($getCliente->resultado_factura_electronica);
                $data = array();
                $data["razon_social"]   =     $getCliente->empresa_razon_social;
                $data["nit"]            =     $getCliente->nit;
                $data["direccion"]      =     $getCliente->direccion;
                $data["empresa_dv"]     =     $getCliente->empresa_dv;
                $data["ciudad"]         =     $getCliente->ciudad;
                $data["regimen"]        =     $getCliente->regimen;
                $data["anexo_factura"]  =     $getCliente->anexo_factura;
                $data["fecha_resolucion"]   = $getCliente->fecha_resolucion;
                $data["rango_inicio"]       = $getCliente->rango_inicio;
                $data["rango_final"]        = $getCliente->rango_final;
                $data["prefijo"]            = $getCliente->prefijo;
                $data["num_resolucion"]     = $getCliente->num_resolucion;
                $data["vigencia"]           = $getCliente->vigencia;
                $data["facturado_fecha"]    = $getCliente->facturado_fecha;
                $data["cufe"]               = $resultadoFact["cufe"];
                $data["xml"]                = $resultadoFact["respuesta_final_dian"];
                $data["qr"]                 = $resultadoFact["url_qr"];
                $data["factura"]            = $getCliente->factura;
                $data["refiere"]            = $getCliente->refiere;
                $data["clientes_razon_social"] = $getCliente->clientes_razon_social;
                $data["tipo_cliente"]           = $getCliente->tipo_cliente;
                $data["documento"]              = $getCliente->documento;
                $data["clientes_dv"]            = $getCliente->clientes_dv;
                $data["nombres_cliente"]        = $getCliente->nombres_cliente;
                $data["celular_a"]              = $getCliente->celular_a;
                $data["celular_b"]              = $getCliente->celular_b;
                $data["direcccion_facturacion"] = $getCliente->direcccion_facturacion;
                $data["barrio"]                 = $getCliente->barrio;
                $data["departamento"]           = $getCliente->departamento;
                $data["municipio"]              = $getCliente->municipio;
                $result = array("success" => true , "error" =>  false, "data" => $data);
            }else{
                $result = array("success" => true , "error" => false , "data" =>"sin registros"  );
            }
            echo json_encode($result);
        }
    }

$run = new ControllerContrato();
switch ($_POST["title"]) {
    case 'findByCus':
        $run->findByCus($_POST['contrato']);
        break;

    case 'findByFactura':
        $run->findByFactura($_POST['factura'] , $_POST['id_servicio']);
        break;
    default:
        # code...
        break;
}



?>