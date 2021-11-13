<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require '../../vendor/autoload.php';
$config = ["settings" => [
    "displayErrorDetails" => true
]]; 
$app = new \Slim\App;
//cargamos las rutas
require_once("../routes/routers.php");


$app->run();