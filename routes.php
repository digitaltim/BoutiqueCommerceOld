<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$slim->get('/hello/{name}', function (Request $request, Response $response) {
    $name = $request->getAttribute('name');
    $response->getBody()->write("Hello, $name");

    return $response;
});

$slim->get('/', 'HomeController:index');
$slim->get('/home', 'HomeController:index');

//$slim->get("/", function ($request, $response, $args) {
//    var_dump($request);
//    throw new \Exception("oops");
//});
