<?php
require_once("../config/Config.php");
//extendemos de la clase Config para conexión a la base de datos
class Cliente extends Config{
 
    public $error;

    public function setError($error){
		$this->error = $error;
	}	

	public function getError(){
		return $this->error;
	}
  
    //consultamos todos los contratos activos(1), cortados(2)
    public  function getAll($documento){

        $sql ="SELECT 
        clientes.documento, 
        clientes.id_cliente, 
        concat(clientes.apellido_paterno ,' ', clientes.apellido_materno,' ', clientes.nombre_primer, ' ',clientes.nombre_segundo) as nombres
         
         FROM clientes
         INNER JOIN contratos on contratos.id_cliente = clientes.id_cliente
         WHERE contratos.id_servicio IN (1,2,3,4,5,6,7,8,9) AND clientes.tipo_cliente = 'N' AND clientes.documento = :documento AND contratos.estado IN (1,2,5)
         limit 0,1";
        
        try{
            $conn = self::Connection();
            $stm    =   $conn->prepare($sql);
            $stm->bindParam(":documento" , $documento , PDO::PARAM_STR);
            $stm->execute();
            if($stm->rowCount() > 0 ){  
                    return $stm->fetch(PDO::FETCH_OBJ);
            }else{
                     
                return false;
            }
                
             
            

        }catch(PDOException $e){
			$this->setError($e->getMessage());
			
		}
        
        

    }
//Funcion para cargar todas las deudas de cada contrato, esta es llamada por una foreach de la funcion getAll
    public function findByDeudas($id_contrato) { 
        $sql = "SELECT 
        deudasb.id_contrato, 
        concat(contratos.id_contrato, 'A',clientes.documento) as referencia,
        SUM(if(deudasb.estado = '1' || deudasb.estado = '3', round(deudasb.valor_total), 0)) AS total_debe,
        SUM(if(deudasb.estado = '1' || deudasb.estado = '3', round(deudasb.valor_parcial), 0)) AS total_abonos
          FROM contratos
        INNER JOIN deudas as deudasb ON deudasb.id_contrato = contratos.id_contrato
        INNER JOIN clientes ON contratos.id_cliente = clientes.id_cliente
        WHERE deudasb.id_contrato = :contrato limit 0,1";

        try {
            $conn = self::Connection();
            $stm    = $conn->prepare($sql);
            $stm->bindParam(":contrato" , $id_contrato , PDO::PARAM_INT);
            $stm->execute();
            if($stm->rowCount() > 0){
                 $resultado = $stm->fetch(PDO::FETCH_OBJ); 
                 return $resultado;
            }else{
                return false;
            }
        } catch (PDOException $e) {
            $this->setError($e->getMessage());
        }
    }

    public function countContrato($id_cliente)
    {
        $sql = "SELECT count(contratos.id_contrato) as valor FROM contratos 
        where contratos.id_servicio IN (1,2,3,4,5,6,7,8,9) AND contratos.id_cliente = :id_cliente AND contratos.estado IN (1,2,5)";

        try{
            $conn = self::Connection();
            $stm    = $conn->prepare($sql);
            $stm->bindParam(":id_cliente" , $id_cliente , PDO::PARAM_INT);
            $stm->execute();
            if($stm->rowCount() > 0 )
            { 
                 
                return $stm->fetch(PDO::FETCH_OBJ);
            }else{
                 
                return false;
            }
        }catch (PDOException $e) {
            $this->setError($e->getMessage());
        }
    }

    public function contratos($id_cliente , $count)
    {
        $sql = "SELECT contratos.id_contrato FROM contratos 
        where contratos.id_servicio IN (1,2,3,4,5,6,7,8,9) AND contratos.id_cliente = :id_cliente AND contratos.estado IN (1,2,5)";
        try{
            $conn = self::Connection();
            $stm    = $conn->prepare($sql);
            $stm->bindParam(":id_cliente" , $id_cliente , PDO::PARAM_INT);
            $stm->execute();
            if($count == 1 )
            {
                return $stm->fetch(PDO::FETCH_OBJ);
            }else{
                return $stm->fetchAll(PDO::FETCH_OBJ);
            }

        }catch (PDOException $e) {
            $this->setError($e->getMessage());
        }
    }

}



?>