<?php
declare(strict_types=1);

namespace It_All\BoutiqueCommerce\Controllers;

use Slim\Container;
use It_All\ServicePg\QueryBuilder;

class AuthController extends Controller
{
    public function index($request, $response)
    {
        return $this->view->render($response, 'auth.twig', ['title' => 'Sign In']);
    }

    function post($request, $response, $args)
    {
        $q = new QueryBuilder("SELECT name, password FROM admins WHERE name = $1 ", $request->getParam('name'));
        $rs = $q->execute();
        $row = pg_fetch_assoc($rs);
        if (!$row) {
            return $response->withRedirect($this->router->pathFor('auth.signin'));
        }
        if (password_verify($request->getParam('password'), $row['password'])) {
            return $response->withRedirect($this->router->pathFor('home'));
        }
        return $response->withRedirect($this->router->pathFor('auth.signin'));
    }

    public function getSignup($request, $response)
    {
        return $this->view->render($response, 'signup.twig', ['title' => 'Sign Up']);
    }

    function postSignup($request, $response, $args)
    {
        pg_insert($this->db->getPgConn(), 'admins', [
            'name' => $request->getParam('name'),
            'password' => password_hash($request->getParam('password'), PASSWORD_DEFAULT),
        ]);
        return $response->withRedirect($this->router->pathFor('home'));
    }
}