<?php
require 'vendor/autoload.php';

require __DIR__.'/.env.php';
 

use GuzzleHttp\Client;

use GuzzleHttp\Psr7\Request;

use  GuzzleHttp\Exeption\RequestException;

use  GuzzleHttp\Exeption\ClientException;

class ApiToken {


	private $url_baseInternet;

	private $url_baseControl;

	private $username;

	private $password;

	private $token; 

	private $client;

	private $request;

	function __construct($url_baseInternet , $url_baseControl , $username , $password , $token){	
		
		$this->url_baseInternet = $url_baseInternet;

		$this->url_baseControl  = $url_baseControl;

		$this->username 	= $username;

		$this->password = 	$password;

		$this->token = $token;

		$this->client = new Client();

	}

	############################################################################
	#####################################endpoint Cpanel Internet###############
	############################################################################
	#Autenticación a la api 
	public function getToken()
    { 
        $url = $this->url_baseInternet.'auth/login';

        $method = 'POST';

      try {

      		$headers = [
      				'Accept'	=> 'application/json',
      				'Content-Type' => 'application/json'
      				];

      		$json  =  [

      				'email' => $this->username,
      				'password' => $this->password
      			];

      		$send = $this->sendEndPoint($json , $headers , $method , $url  );

      		if($send->getStatusCode() == 200)
      		{
      			$response = json_decode($send->getBody()->getContents());

      			return $response->response;
      		}
      }catch(\GuzzleHttp\Exeption\RequestException $e){

      	$error['error'] = $e->getMessage();

      	$error['request'] = $e->getRequest();

      	if ($e->hasResponse()) {

      		if ($e->getResponse()->getStatusCode() == '400') {

      			$error['response'] = $e->getResponse();
      		}
      	}

      }

  }


  #Conteo de registros en la base de datos de cpanel
  #POST

  public function countInternet($token , $fecha)
  {
  	$url = $this->url_baseInternet.'merkas/count';
  	
  	$method = 'POST';
  	
  	$json = [
  		'fecha' => $fecha
  	];

  	$headers = [
  			'Accept' => 'application/json',
  			'Content-Type' => 'application/json',
  			'Authorization' => 'Bearer '.$token
  		];

  	try {
  		
  		$send = $this->sendEndPoint($json , $headers , $method , $url  );

  		if($send->getStatusCode() == 200)
  		{
  			$response = json_decode($send->getBody()->getContents());

  			return $response->response;
  		}
  		

  	}catch (RuntimeException $e) {
    // catches all kinds of RuntimeExceptions
    	if ($e instanceof ClientException) {
         
         return false;
    	
    	} else if ($e instanceof RequestException) {
         
         return false;
    	}
	}
  }

  #retorno de registros en cpanel internet
  #POST
  public function pagosInternet($token , $fecha){

  	$url 	= $this->url_baseInternet.'merkas/all';

  	$method = 'POST';

  	$json = [
  		'fecha' => $fecha
  	];

  	$headers = [
  			'Accept' => 'application/json',
  			'Content-Type' => 'application/json',
  			'Authorization' => 'Bearer '.$token
  		];

  	try {
  		
  		$send = $this->sendEndPoint($json , $headers , $method , $url );

  		if($send->getStatusCode() == 200)
  		{
  			$response = json_decode($send->getBody()->getContents());

  			return $response->response;
  		}

  	}catch(\GuzzleHttp\Exeption\RequestException $e){
      	
      	$error['error'] = $e->getMessage();
      	
      	$error['request'] = $e->getRequest();
      	
      	if ($e->hasResponse()) {
      	
      		if ($e->getResponse()->getStatusCode() == '400') {
      	
      			$error['response'] = $e->getResponse();
      		}
      	}

      }
  }

  #function save rcpagos 
  #post
  public function saveInternet($token , $params){

  				$headers = [
  					'Accept' => 'application/json',
  					'Content-Type' => 'application/json',
  					'Authorization' => 'Bearer '.$token
  				];

  				$json = [
  					"id_servicio_rc"=> $params['id_servicio_rc'],
            		"rc" => $params['rc'],
            		"valor" => $params['valor'],
            		"celular" => $params['celular'],
            		"fecha" => $params['fecha']
  				];

  				$url = $this->url_baseInternet.'merkas/save';
  				
  				$method = 'POST';
  				
  				try{
  					$send = $this->sendEndPoint($json  , $headers , $method , $url  );

  					if($send->getStatusCode() == 200) {

  						$response = json_decode($send->getBody()->getContents());

  						return $response->response;

  					}
  				}catch(\GuzzleHttp\Exeption\RequestException $e){
      	
      					$error['error'] = $e->getMessage();
      	
      					$error['request'] = $e->getRequest();
      	
      				if ($e->hasResponse()) {
      	
      					if ($e->getResponse()->getStatusCode() == '400') {
      	
      						$error['response'] = $e->getResponse();
      					}
      				}

      				}
  				
  }

    #consultar recibos de caja en estado 0 y fecha
	#GET
	public function QueryByEstadoCero($token)
	{
		$headers = [
			'Accept' => 'application/json',
			'Content-Type' => 'application/json',
			'Authorization' => 'Bearer '.$token
		];

		$url = $this->url_baseInternet.'merkas/count/estado';

		$method = 'GET';

		try {
			$send = $this->sendEndPoint($json = null, $headers , $method , $url);

			if($send->getStatusCode() == 200)
			{
				$reponse = json_decode($send->getBody()->getContents());

				return $response->response;
			}
		} catch(\GuzzleHttp\Exeption\RequestException $e){
      	
      					$error['error'] = $e->getMessage();
      	
      					$error['request'] = $e->getRequest();
      	
      				if ($e->hasResponse()) {
      	
      					if ($e->getResponse()->getStatusCode() == '400') {
      	
      						$error['response'] = $e->getResponse();
      					}
      				}

      			}
		
	}

	public function sendEndPoint($params = null, $headers = null , $method, $url )
	{	
			if ($headers == null) {
				$headers = [
					'Content-Type' => 'application/json',
					'Accept' => 'application/json'
				];
			}
			$send = new Request($method , $url);
   
			return  $this->client->send($send ,  ['headers' => $headers ,'json'	=>	$params]);
		 
	}
#############################################################################
#####################Function Controlmas####################################
#############################################################################
  #Conteo de registros en controlmas

  	public function countControl($fecha)
  	{
      $url = $this->url_baseControl;
  		  $data = array(
            "dia"=> $fecha,
            "key" => $this->token,
            "m" => 3
        );
        $ch =   curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        $response = json_decode(curl_exec($ch));
        curl_close($ch);
        if($response->success==false) {
                return false;
        }else{
                return $response->data;
        }
  	}

#consultar pagos del dia en controlmas 
  public function pagosControl($fecha)
  {
  	$url = $this->url_baseControl;
        $data = array(
            "dia"=> $fecha,
            "key" => $this->token,
            "m" => 2
        );
        $ch =   curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        $response = json_decode(curl_exec($ch));
        curl_close($ch);
        if($response->success==false) {
                return false;
        }else{
                return $response->data;
        }
  }


###############################################################################################
#############################endpoint Merkas##################################################
#post
#generar token
	public function generateTokenMerkas($token , $keyComercio , $url)
	{ 
		 
		$json = [
			"create_token"=> $token,
			"token" => $keyComercio
		];

		$urlGenerateToken = $url;

		$method = 'POST';

		try {
			$send = $this->sendEndPoint($json, $headers = null , $method , $urlGenerateToken);

			if($send->getStatusCode() == 200)
			{
				$response = json_decode($send->getBody()->getContents());

				return $response->mensaje;
			}
		} catch(\GuzzleHttp\Exeption\RequestException $e){
      	
      					$error['error'] = $e->getMessage();
      	
      					$error['request'] = $e->getRequest();
      	
      				if ($e->hasResponse()) {
      	
      					if ($e->getResponse()->getStatusCode() == '400') {
      	
      						$error['response'] = $e->getResponse();
      					}
      				}

      			}
	}
	#registrar venta
	#post
	public function registrarVenta($params , $url)
	{
		 $json = [
			"usuario_telefono" => $params["usuario_telefono"],
			"factura_pago_efectivo" => $params["factura_pago_efectivo"],
			"factura_numero" => $params["factura_numero"],
			"cajero" => [
				"usuario_id" => $params["cajero"]
			],
			"token" => $params["token"]
		];
		$method = "POST";

		try {
			
			$send = $this->sendEndPoint($json , $headers = null, $method , $url);

			if($send->getStatusCode() == 200)
			{
				$response = json_decode($send->getBody->getContents());

				return $response;
			}

		} catch(\GuzzleHttp\Exeption\RequestException $e){
      	
				$error['error'] = $e->getMessage();

				$error['request'] = $e->getRequest();

			if ($e->hasResponse()) {

				if ($e->getResponse()->getStatusCode() == '400') {

					$error['response'] = $e->getResponse();
				}
			}

		}
	}
	
	
 

  public function getDuplicate($arrayInternet , $value)
  {
  	$duplicate =  false;

  	foreach ($arrayInternet as $key) {

  		if ($key->id_servicio_rc == $value) {

  			$duplicate = true;

  			break;

  		} else {
  			$duplicate = false;
  		}
  	}

  	return $duplicate;
  }


}	
####################################################################################################################################################
####################################################################################################################################################
################################################Ejecución de script#################################################################################
####################################################################################################################################################

if(!empty(TOKEN_ACCESO)){
#carga constructor de la case, agregar las variables 
#url Api internet
#url api control
#Autenticación API internet
#Token api Control

$apiToken = new ApiToken(URL_BASIC_INTERNET, URL_BASIC_CONTROL, USERNAME , PASSWORD , TOKEN);

#se Obtiene el token
#$token = $apiToken->getToken();

#$dia = date("Ymd");
$dia = date("Ymd");
#1. CONTAR EN CONTROL MAS LA CANTIDAD DE REGISTROS
$getCountControl = $apiToken->countControl($dia);
#2.Contar en cpanel internet la cantidad de registros
$getCountInternet = $apiToken->countInternet($token , $dia);

#valida si el retorno del count es falso significa que no hay ningun registros
#procede a ingresar los datos a cpanel internet

if($getCountInternet == false){

	#consulta pagos de control del dia Ymd
	$pagosControl = $apiToken->pagosControl($dia);

  if($pagosControl != "sin registros"){
	#el retorno esta en staclassObject
	foreach ($pagosControl as $key ) {
		#almacena en un array temporal 
		$pagosBefore['id_servicio_rc']  = $key->id;
        
        $pagosBefore['rc']    = $key->rc;
        
        $pagosBefore['valor'] = $key->valor;
        
        if ($key->celular == 0){ $pagosBefore['celular'] = "1"; } else {$pagosBefore['celular'] = $key->celular; }
        
        $pagosBefore['fecha'] = $key->fecha;
        
        #se instancia la funcion de guardar en cpanel internet
        $saveInternet = $apiToken->saveInternet($token , $pagosBefore);
	   }
    }

  }elseif($getCountControl > $getCountInternet) {
   #comparar los valores 
   #Despues de haber registros en el día, compara los valores de controlmas con internet 
	 #en caso que sea mayor el count en controlmas 

	#registros existentes en cPanel Internet
	$pagosInternet = $apiToken->pagosInternet($token , $dia);


  if($pagosControl != "sin registros"){
	#se procede a realizar una consulta de todos los registros
	$pagosControl = $apiToken->pagosControl($dia);
	
	#recorremos el resultado de control mas classobj para almacenar en el arregloControl
	
	foreach ($pagosControl as $key ) {

		$duplicate = $apiToken->getDuplicate($pagosInternet , $key->id);


		if($duplicate == false )
		{
			$pagosBefore['id_servicio_rc']  = $key->id;
        
        	$pagosBefore['rc']    = $key->rc;
        
        	$pagosBefore['valor'] = $key->valor;
        
        	if ($key->celular == 0){ $pagosBefore['celular'] = "1"; } else {$pagosBefore['celular'] = $key->celular; }
        
        	$pagosBefore['fecha'] = $key->fecha;

			$saveInternet = $apiToken->saveInternet($token , $pagosBefore);
		}
		
	}

	
}
	
}

#############################################################################################################################
######################################Registro en Merkas de los las facturas#################################################
#############################################################################################################################
#3e1d7ed98e94366975582f41f77a0bc9442a288da87d164bdc9fef66e57de70f
#TOKEN API 7242b219185a6ecd76e2f0de1a178928
#anular con endpoint cancelar factura con el listar_facturacion {aliado_merkas_factura_id}
#end point registar_compra no valida duplicidad de factura
*/
	if(ACTIVE){
		#REALIZAR BUSQUEDA DEL COUNT EN INTERNET DE LOS RECIBOS CARGADOS AL SISTEMA
		
		$queryByEstadoCero = $apiToken->QueryByEstadoCero($token);

		if($queryByEstadoCero != "sin registros")
		{
			$tokenUniqid = uniqid();

			$generateToken = $apiToken->generateTokenMerkas($tokenUniqid , TOKEN_COMERCIO_MERKAS , ENDPOINT_TOKEN);
			
			foreach($queryByEstadoCero as $key)
			{
				/**"id": 2723,
            "id_servicio_rc": "1-146737",
            "rc": "146737",
            "valor": 45100,
            "celular": "3214902250",
            "estado": 0,
            "log": "2022-01-15 07:55:03: creado desde servidor",
            "created_at": "2022-01-15T12:55:03.000000Z",
            "updated_at": "2022-01-15T12:55:03.000000Z",
            "fecha": "20220115"*/
			$registrarVenta ["usuario_telefono"] = $queryByEstadoCero->celular;

			$registrarVenta ["factura_pago_efectivo"] = $queryByEstadoCero->valor;

			$registrarVenta ["factura_numero"] = $queryByEstadoCero->rc;

			$registrarVenta ["cajero"]	= ID_USUARIO;

			$registrarVenta ["token"] = $tokenUniqid;

			$setRegistrarVenta = $apiToken->registrarVenta($registrarVenta , ENDPOINT_REGISTRO_VENTA);

			 #en caso de retornar true, se registra 1 en cpanel true
			if($setRegistrarVenta == true)
			{
				#actualizar pagoMerkas con 1 cargado existosamente
				$paramUpdate["log"] = $queryByEstadoCero->log.','.date().': registro Existos, servidor merkas devolvio true';
				$paramUpdate["estado"] = '1';
				
			}elseif($setRegistrarVenta == false){
				#en caso de retornar falso, se registra 2 en cpanel error
				$paramUpdate["log"] = $queryByEstadoCero->log.','.date().': error false, por parte de servidor Merkas';
				$paramUpdate["estado"] = '2';
			}else{
				#en caso de retornar falso, se registra 2 en cpanel error
				$paramUpdate["log"] = $queryByEstadoCero->log.','.date().': '.$setRegistrarVenta->mensaje;
				$paramUpdate["estado"] = '2';
			}

			$setUpdateRcTrue = updateRcCpanel($paramUpdate , $token)

			}
		}
	}


}else{

	echo "Error de Token";
}



?>