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

      		$send = $this->sendEndPoint($json , $headers , $method , $url);

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
  		
  		$send = $this->sendEndPoint($json , $headers , $method , $url);

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
  		
  		$send = $this->sendEndPoint($json , $headers , $method , $url);

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
  					$send = $this->sendEndPoint($json  , $headers , $method , $url);

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

  #Conteo de registros en controlmas

  	public function countControl($fecha)
  	{
  		$url = $this->url_baseControl;
  		$method = 'POST';
  		$json = [
  			'key' => $this->token,
  			'm' => '3',
  			'dia' => $fecha
  		];

  		$send = $this->sendEndPoint($json , $headers = null , $method , $url);

  		if ($send->getStatusCode() == 200) {
  			
  			$response  = json_decode($send->getBody()->getContents());

  			return $response->data;

  		}
  	}

#consultar pagos del dia en controlmas 
  public function pagosControl($fecha)
  {
  	$url =  $this->url_baseControl;
  	$method = 'POST';
  	$json 	= [
  		'key' => $this->token,
  		'm' => '2',
  		'dia' => $fecha
  	];

  	$send = $this->sendEndPoint($json , $headers=null, $method , $url);
  	if($send->getStatusCode() == 200)
  	{
  		$response = json_decode($send->getBody()->getContents());

  		return $response->data;
  	}
  }

  public function sendEndPoint($params = null, $headers = null , $method, $url)
  {	
  		if ($headers == null) {
  			$headers = [
  				'Content-Type' => 'application/json',
  				'Accept' => 'application/json'
  			];
  		}
  		$send = new Request($method , $url);

  		return  $this->client->send($send ,  [
  			'headers' => $headers ,
  			'json'	=>	$params
  		]);
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


if($_GET['xscc'] == TOKEN_ACCESO){

$apiToken = new ApiToken(URL_BASIC_INTERNET, URL_BASIC_CONTROL, USERNAME , PASSWORD , TOKEN);

$token = $apiToken->getToken();

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

#comparar los valores 
#Despues de haber registros en el día, compara los valores de controlmas con internet 

if ($getCountControl > $getCountInternet) {
	#en caso que sea mayor el count en controlmas 

	#registros existentes en cPanel Internet
	$pagosInternet = $apiToken->pagosInternet($token , $dia);



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

#registrar los nuevos valores en cpanel internet
}else{

	echo "Error de Token";
}



?>