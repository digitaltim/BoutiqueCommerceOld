<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$slim->get('/home', function ($request, $response) {
    return $this->view->render($response, 'home.twig');
});


$slim->get('/hello/{name}', function (Request $request, Response $response) {
    $name = $request->getAttribute('name');
    $response->getBody()->write("Hello, $name");

    return $response;
});

//$slim->get('/', 'HomeController:index');
$slim->get('/', 'HomeController:index');
