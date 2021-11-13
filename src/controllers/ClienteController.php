<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app->post('/cliente', function (Request $request, Response $response, array $args) {
    require_once("../controllers/ClienteDAO.php");

    $cliente = ClienteDAO::cliente($request->getParam('documento'));
});



?>