<?php
declare(strict_types=1);

namespace It_All\BoutiqueCommerce\Controllers;

use Slim\Container;
use Respect\Validation\Validator as v;
use It_All\BoutiqueCommerce\Utilities\Database;
use It_All\BoutiqueCommerce\UI\Views\AuthenticationView;
use It_All\BoutiqueCommerce\Models\Admins;

class AuthController extends Controller
{
    function postSignIn($request, $response, $args)
    {
        $rules = [];
        $rules['username'] = ['required'];
        $rules['password'] = ['required'];

        if (!$this->validator->validate($request->getParsedBody(), $rules)) {
            // redisplay the form with input values and error(s)
            $authenticationView = new AuthenticationView($this->container);
            return $authenticationView->getSignIn($request, $response, $args);
        }

        $auth = $this->auth->attempt(
            $request->getParam('username'),
            $request->getParam('password')
        );

        if ($auth) {
            $this->logger->addInfo($request->getParam('username').' logged in.');
            return $response->withRedirect($this->router->pathFor('crud.show', ['table' => 'admins']));
        }

        $generalErrorMessage = 'Could not sign you in with those credentials.';

        // redisplay the form with input values and error(s)
        $authenticationView = new AuthenticationView($this->container);
        return $authenticationView->getSignIn($request, $response, $args, $generalErrorMessage);
    }

    function postSignUp($request, $response, $args)
    {
        $adminsModel = new Admins($this->db);

        $rules = [];
        $rules['username'] = ['required'];
        $rules['password'] = ['required'];

        if (!$this->validator->validate($request->getParsedBody(), $rules)) {
            // redisplay the form with input values and error(s)
            $authenticationView = new AuthenticationView($this->container);
            return $authenticationView->getSignUp($request, $response, $args);
        }

        if ($adminsModel->checkRecordExistsForUsername($request->getParam('username'))) {
            $generalErrorMessage = 'Username already exists.';
            // redisplay the form with input values and error(s)
            $authenticationView = new AuthenticationView($this->container);
            return $authenticationView->getSignUp($request, $response, $args, $generalErrorMessage);
        }

        $res = $adminsModel->insert([
            'username' => $request->getParam('username'),
            'password_hash' => password_hash($request->getParam('password'), PASSWORD_DEFAULT),
        ]);

        if ($res) {
            $this->logger->addInfo('New user '.$request->getParam('username').' added.');
            $this->flash->addMessage('info', 'You have been signed up.');

            $this->auth->attempt(
                $request->getParam('username'),
                $request->getParam('password')
            );

            return $response->withRedirect($this->router->pathFor('crud.show', ['table' => 'admins']));
        }

        $generalErrorMessage = 'Error inserting new username.';

        // redisplay the form with input values and error(s)
        $authenticationView = new AuthenticationView($this->container);
        return $authenticationView->getSignUp($request, $response, $args, $generalErrorMessage);
    }
}