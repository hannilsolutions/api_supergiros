<?php

require 'vendor/autoload.php';
/*
$client = new GuzzleHttp\Client();
$res = $client->request('POST', 'https://apps.internetinalambrico.com.co/v2021/public/auth/login', [
    'form_params' => [
    	'email' => 'web@internetinalambrico.com.co', 
    	'password' => 'E$LaClav3']
]);

if ($res->getStatusCode() == 200)
{
	$contents = json_decode($res->getBody());
	echo $contents->response;
} */

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;


class mainSyncronic {


	private $client;

	private $base_url_internet;

	private $request;

	function __construct(){

		$this->client = new Client();

		$this->base_url_internet = 'https://apps.internetinalambrico.com.co/v2021/public/';
		
	}

	public  function loginAppInternetInalambrico(){

		$params=[
			'email'		=>'web@internetinalambrico.com.co',
			'password' 	=> 'E$LaClav3'
		];
		
		$url = 'auth/login';

		$method = 'POST';
		
		$send = $this->sendEndPoint($method , $url , $params);
		
		$response = json_decode($send->getBody());

		return $response->response;
	}

	/**
		function para enviar cualquier clase de endpoint
	*/

	public  function sendEndPoint($method , $url , $params = null ,$headers = null){

		$send = new Request($method , $this->base_url_internet.$url);

		return $this->client->send($send , [ 'form_params' => $params], ['headers'=>$headers], ['timeout' => 10]);
	}

	#traer todos los pagos de la tabla pagos

	public function getAllPagos(){
		
			$login = $this->loginAppInternetInalambrico();
		
			$url 	= 'pagos/all';
		
			$method = 'GET';
		
			$headers = [
				#'Content-Type' => 'application/json' , 
				'Authorization' => 'Bearer '.$login
				#'Accept-Encoding' => 'gzip, deflate, br',
				#'Accept'	=> 'application/json',
				#'Connection' => 'keep-alive'
			];
			
		
			$send 	= $this->sendEndPoint($method , $url , $params = null , $headers);
		
			if($send->getStatusCode()==200){
		
			$response = json_decode($send->getBody());
		
			echo $response;

		}elseif($getStatusCode() == 401){
		
			echo 'Error 401';
		}


	}
}

$run = new mainSyncronic();

$run->getAllPagos();
?>