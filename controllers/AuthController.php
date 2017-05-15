<?php
declare(strict_types=1);

namespace It_All\BoutiqueCommerce\Controllers;

use Slim\Container;
use Respect\Validation\Validator as v;
use It_All\BoutiqueCommerce\Utilities\Database;

class AuthController extends Controller
{
    function postSignIn($request, $response, $args)
    {
        $auth = $this->auth->attempt(
            $request->getParam('username'),
            $request->getParam('password')
        );

        if ($auth) {
            return $response->withRedirect($this->router->pathFor('test'));
        }

        $this->flash->addMessage('error', 'Could not sign you in with those credentials.');
        return $response->withRedirect($this->router->pathFor('auth.signin'));
    }

    function postSignUp($request, $response, $args)
    {
        $validation = $this->validator->validate($request, [
            'username' => v::notEmpty()->alpha()->usernameAvailable(),
            'password' => v::noWhitespace()->notEmpty(),
        ]);

        if ($validation->failed()) {
            return $response->withRedirect($this->router->pathFor('auth.signup'));
        }


        $res = pg_insert($this->db->getPgConn(), 'admins', [
            'username' => $request->getParam('username'),
            'password_hash' => password_hash($request->getParam('password'), PASSWORD_DEFAULT),
        ]);

        if ($res) {
            $this->flash->addMessage('info', 'You have been signed up.');
            $this->auth->attempt($request->getParam('username'), $request->getParam('password'));
            return $response->withRedirect($this->router->pathFor('test'));
        }
        return $this->view->render($response, 'signup.twig', ['title' => 'Sign Up']);
    }
}