<?php
declare(strict_types=1);

namespace It_All\BoutiqueCommerce\Controllers;

use Slim\Container;
use It_All\BoutiqueCommerce\Utilities\Database;

class AuthController extends Controller
{
    public function getSignIn($request, $response)
    {
        return $this->view->render($response, 'admin/auth/auth.twig', ['title' => 'Sign In']);
    }

    function postSignIn($request, $response, $args)
    {
        // $q = new Database\QueryBuilder("SELECT id, username, password FROM admins WHERE username = $1 ", $request->getParam('username'));
        // $rs = $q->execute();
        // $row = pg_fetch_assoc($rs);
        
        // if (!$row) {
        //     return $response->withRedirect($this->router->pathFor('auth.signin'));
        // }
        
        // if (password_verify($request->getParam('password'), $row['password'])) {
        //     $_SESSION['username'] = $row['id'];

        $auth = $this->auth->attempt(
            $request->getParam('username'),
            $request->getParam('password')
        );

        if ($auth) {
            return $response->withRedirect($this->router->pathFor('home'));
        }
        
        return $response->withRedirect($this->router->pathFor('auth.signin'));
    }

    public function getSignUp($request, $response)
    {
        return $this->view->render($response, 'admin/auth/signup.twig', ['title' => 'Sign Up']);
    }

    function postSignUp($request, $response, $args)
    {
        pg_insert($this->db->getPgConn(), 'admins', [
            'username' => $request->getParam('username'),
            'password' => password_hash($request->getParam('password'), PASSWORD_DEFAULT),
        ]);
        return $response->withRedirect($this->router->pathFor('home'));
    }
}