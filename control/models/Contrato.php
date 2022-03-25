<?php
require_once("../config/Config.php");
//extendemos de la clase Config para conexión a la base de datos
class Contrato extends Config{
 
    public $error;

    public function setError($error){
		$this->error = $error;
	}	

	public function getError(){
		return $this->error;
	}
   

    public  function findByCus($contrato){

        $sql ='SELECT 
        c.id_contrato,
        concat(d.a_tipo , " ", d.a_numero,d.a_letra," ",d.b_tipo," ",d.b_numero,d.b_letra," ",d.numero , "/",d.barrio,"/",m.municipio , "/",dep.departamento) as direccion
        FROM controlmas.direcciones as d
        inner join contratos as c on d.id_direccion = c.id_direccion_servicio
        inner join lista_municipios as m on m.id_municipio = d.municipio
        inner join lista_departamentos as dep on dep.id_departamento = d.departamento
        WHERE c.id_contrato = :id_contrato';
        
        try{
            $conn = self::Connection();
            $stm    =   $conn->prepare($sql);
            $stm->bindParam(":id_contrato" , $contrato , PDO::PARAM_INT);
             
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

    public function findByFactura($factura, $idServicio)
    {
        $sql = "SELECT 
        empresas.razon_social as empresa_razon_social,
        empresas.nit,
        empresas.direccion,
        empresas.dv as empresa_dv,
        empresas.ciudad,
        empresas.regimen,
        empresas.anexo_factura,
        resoluciones.fecha_resolucion,
        resoluciones.rango_inicio,
        resoluciones.rango_final,
        resoluciones.prefijo,
        resoluciones.num_resolucion,
        resoluciones.vigencia,
        deudas.facturado_fecha,
        deudas.resultado_factura_electronica,
        deudas.factura,
        deudas.refiere,
        clientes.razon_social as clientes_razon_social,
        clientes.tipo_cliente,
        clientes.documento,
        clientes.dv as clientes_dv,
        concat(clientes.nombre_primer,' ', clientes.nombre_segundo,' ',clientes.apellido_paterno,' ',clientes.apellido_materno) as nombres_cliente,
        clientes.celular_a,
        clientes.celular_b,
        concat(direcciones.a_tipo,' ',direcciones.a_numero,' ',direcciones.a_letra,' ',direcciones.b_tipo,' ',direcciones.b_numero,' ',direcciones.b_letra,' ',direcciones.numero) as direcccion_facturacion,
        direcciones.barrio,
        lista_departamentos.departamento,
        lista_municipios.municipio
        from deudas
        INNER JOIN clientes ON clientes.id_cliente = deudas.id_cliente
        INNER JOIN empresas ON empresas.id_empresa = deudas.id_empresa 
        INNER JOIN resoluciones ON resoluciones.id_resolucion = empresas.id_resolucion_activa
        INNER JOIN contratos ON contratos.id_contrato = deudas.id_contrato
        INNER JOIN  direcciones ON contratos.id_direccion_factura = direcciones.id_direccion
        INNER JOIN lista_municipios ON lista_municipios.id_municipio = direcciones.municipio
        INNER JOIN lista_departamentos ON lista_departamentos.id_departamento = direcciones.departamento
        WHERE deudas.id_servicio = :id_servicio AND deudas.factura = :factura 
        group by deudas.id_empresa limit 0,1";
        try{
            $conn = self::Connection();
            $stm    =   $conn->prepare($sql);
            $stm->bindParam(":id_servicio" , $idServicio , PDO::PARAM_INT);
            $stm->bindParam(":factura" , $factura , PDO::PARAM_INT);             
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
}



?>