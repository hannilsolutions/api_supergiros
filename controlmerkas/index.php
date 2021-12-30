<?php





/*

tenemos que realizar un api para guardar la información en pagos_merkas
puede ser el de v2021 la app que ya se creo

*/


/*
1. realizar una consulta a la base de datos de controlmas para validar la cantidad de registros por servicio
2. realizar consulta a servidor internetinalambrico por id servicio 
*/

/**
 * 
 */
class merkasControl  
{
	
	protected $servicio;

	function __construct()
	{
		$this->servicio = array(1,2,3,4,5,6,7,8,9);	

	}

	function apiControl($dia, $servicio = null){
		#consultar api control

		$init = curl_init();
        $url  = "http://localhost/hannilsolutions/internetinalambrico.com.co.apisupergiros/control/public/";
        $data = array(
            "key"		=>	"f24f0aaa81db035965e65f60c5e54c41",
            "m"			=> "2",
            "dia"		=> 20210801#$dia
        );
        $params = json_encode($data);
        curl_setopt($init, CURLOPT_URL, $url);
        curl_setopt($init,CURLOPT_RETURNTRANSFER,true);
        curl_setopt($init, CURLOPT_POSTFIELDS, $params);
        curl_setopt($init,CURLOPT_HEADER, false); 
        if (false === ($obj = curl_exec($init))) {
            throw new Exception('Error: '.curl_error($init));
        }
        curl_close($init);
        $obj=json_decode($obj, true); 
        return $obj;
	}

	function apiControlCount($dia , $servicio = null){
		$init = curl_init();
        $url  = "http://localhost/hannilsolutions/internetinalambrico.com.co.apisupergiros/control/public/";
        $data = array(
            "key"		=>	"f24f0aaa81db035965e65f60c5e54c41",
            "m"			=> "3",
            "dia"		=> 20210801#$dia
        );
        $params = json_encode($data);
        curl_setopt($init, CURLOPT_URL, $url);
        curl_setopt($init,CURLOPT_RETURNTRANSFER,true);
        curl_setopt($init, CURLOPT_POSTFIELDS, $params);
        curl_setopt($init,CURLOPT_HEADER, false); 
        if (false === ($obj = curl_exec($init))) {
            throw new Exception('Error: '.curl_error($init));
        }
        curl_close($init);
        $obj=json_decode($obj, true); 
        return $obj;
	}

	function apiInternetCount($dia , $jwt){
		$headers = [
			'Content-Type: application/json' ,
			'Authorization: Bearer '.$jwt
		];

		$init = curl_init();
        $url  = "http://localhost:3001/public/merkas/count";
        $data = array(
            
            "fecha"		=> "20210801"#$dia
        );
        $params = json_encode($data);
        curl_setopt($init, CURLOPT_URL, $url);
        curl_setopt($init,CURLOPT_RETURNTRANSFER,true);
        curl_setopt($init, CURLOPT_POSTFIELDS, $params);
        curl_setopt($init,CURLOPT_HEADER, $headers); 
        if (false === ($obj = curl_exec($init))) {
            throw new Exception('Error: '.curl_error($init));
        }
        curl_close($init);
        $obj=json_decode($obj, true); 
       
       if($obj['success']){
       		return  $obj['response'];
       }else{
       		return "sin registros";
       }

        

	}

	function inicioSesion(){
		$init = curl_init();
        $url  = "http://localhost:3001/public/auth/login";
        $data = array(
            "email"		=>	"pruebas1@gmail.com",
            "password"	=> "pruebas"
        );
        $params = json_encode($data);
        curl_setopt($init, CURLOPT_URL, $url);
        curl_setopt($init,CURLOPT_RETURNTRANSFER,true);
        curl_setopt($init, CURLOPT_POSTFIELDS, $params);
        curl_setopt($init,CURLOPT_HEADER, false); 
        if (false === ($obj = curl_exec($init))) {
            throw new Exception('Error: '.curl_error($init));
        }
        curl_close($init);
        $obj=json_decode($obj, true); 
        return $obj["response"];
	}
}

function main(){
	try{
		
		$obj = new merkasControl();
		$jwt = $obj->inicioSesion();
		echo $obj->apiInternetCount(date("Ymd") , $jwt);
		var_dump($obj->apiControlCount(date("Ymd")));
		var_dump($obj->apiControl(date("Ymd")));
	}catch(Exception $e){
		echo "Exception capture: ". $e->getMessage()."\n";
	}
}

$app = main();



?>