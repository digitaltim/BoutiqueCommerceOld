<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app->get('/home', function ($request, $response) {
    return $this->view->render($response, 'home.twig');
});


$app->get('/hello/{name}', function (Request $request, Response $response) {
    $name = $request->getAttribute('name');
    $response->getBody()->write("Hello, $name");

    return $response;
});

//$app->get('/', 'HomeController:index');
$app->get('/', 'HomeController:index');
