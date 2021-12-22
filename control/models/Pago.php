<?php
require_once("../config/Config.php");
//extendemos de la clase Config para conexión a la base de datos
class Pago extends Config{
 
    public $error;

    public function setError($error){
		$this->error = $error;
	}	

	public function getError(){
		return $this->error;
	}
  
    //consultamos todos los contratos activos(1), cortados(2)
    public  function getPagos($fecha){

        $sql ="SELECT pagos.valor_cobro, clientes.celular_a , pagos.id_recibo_caja,  
				concat(pagos.id_servicio,'-',pagos.id_recibo_caja) as id
				FROM pagos
				INNER JOIN clientes ON clientes.id_cliente = pagos.id_cliente
				WHERE pagos.fechaf = :fecha AND pagos.id_servicio IN (1,2,3,4,5,6,7,8,9)
				group by pagos.id_recibo_caja";
        
        try{
            $conn = self::Connection();
            $stm    =   $conn->prepare($sql);
            $stm->bindParam(":fecha" , $fecha , PDO::PARAM_INT);
             
            $stm->execute();
            if($stm->rowCount() > 0 ){ 
                    return $stm->fetchAll(PDO::FETCH_OBJ);
            }else{
                return false;
            }
                
             
            

        }catch(PDOException $e){
			$this->setError($e->getMessage());
			
		}
        
        

    }

    public function getCountPago($fecha){
    	$sql = "SELECT pagos.valor_cobro, clientes.celular_a , pagos.id_recibo_caja,  
				concat(pagos.id_servicio,'-',pagos.id_recibo_caja) as id
				FROM pagos
				INNER JOIN clientes ON clientes.id_cliente = pagos.id_cliente
				WHERE pagos.fechaf = :fecha AND pagos.id_servicio IN (1,2,3,4,5,6,7,8,9)
				group by pagos.id_recibo_caja";
		try{
            $conn = self::Connection();
            $stm    =   $conn->prepare($sql);
            $stm->bindParam(":fecha" , $fecha , PDO::PARAM_INT);
             
            $stm->execute();
            $filas = $stm->rowCount();
            if($filas > 0 ){ 
                    return $filas;
            }else{
                return false;
            }
                
             
            

        }catch(PDOException $e){
			$this->setError($e->getMessage());
			
		}
    }


 }