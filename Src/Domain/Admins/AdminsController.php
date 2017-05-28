<?php
declare(strict_types=1);

namespace It_All\BoutiqueCommerce\Src\Domain\Admins;

use It_All\BoutiqueCommerce\Src\Infrastructure\Controller;

class AdminsController extends Controller
{
    function postUpdate($request, $response, $args)
    {
        $_SESSION['formInput'] = $request->getParsedBody();

        $adminsModel = new AdminsModel();

        if (!$this->validator->validate(
            $request->getParsedBody(),
            $adminsModel->getValidationRules())
        ) {
            // redisplay the form with input values and error(s)
            return (new AdminsView($this->container))->getInsert($request, $response, $args);
        }

        if ($adminsModel->checkRecordExistsForUsername($request->getParam('username'))) {
            $_SESSION['generalFormError'] = 'Username already exists';
            // redisplay the form with input values and error(s)
            return (new AdminsView($this->container))->getInsert($request, $response, $args);
        }

        $username = $request->getParam('username');

        $res = $adminsModel->insert([
            'name' => $request->getParam('name'),
            'username' => $username,
            'role' => $request->getParam('role'),
            'password_hash' => password_hash($request->getParam('password'), PASSWORD_DEFAULT),
        ]);

        if ($res) {
            unset($_SESSION['formInput']);
            $message = 'New admin user ' . $username . ' inserted.';
            $this->logger->addInfo($message);
            $this->flash->addMessage('info', $message);

            return $response->withRedirect($this->router->pathFor('admins.show'));
        }

        //
        $_SESSION['generalFormError'] = 'Query Failure';
        // redisplay the form with input values and error(s)
        return (new AdminsView($this->container))->getInsert($request, $response, $args);
    }

    function postInsert($request, $response, $args)
    {
        $_SESSION['formInput'] = $request->getParsedBody();

        $adminsModel = new AdminsModel();

        if (!$this->validator->validate(
            $request->getParsedBody(),
            $adminsModel->getValidationRules())
        ) {
            // redisplay the form with input values and error(s)
            return (new AdminsView($this->container))->getInsert($request, $response, $args);
        }

        if ($adminsModel->checkRecordExistsForUsername($request->getParam('username'))) {
            $_SESSION['generalFormError'] = 'Username already exists';
            // redisplay the form with input values and error(s)
            return (new AdminsView($this->container))->getInsert($request, $response, $args);
        }

        $username = $request->getParam('username');

        $res = $adminsModel->insert(
            $request->getParam('name'),
            $username,
            $request->getParam('role'),
            $request->getParam('password')
        );

        if ($res) {
            unset($_SESSION['formInput']);
            $message = 'New admin user ' . $username . ' inserted.';
            $this->logger->addInfo($message);
            $this->flash->addMessage('info', $message);

            return $response->withRedirect($this->router->pathFor('admins.show'));
        }

        //
        $_SESSION['generalFormError'] = 'Query Failure';
        // redisplay the form with input values and error(s)
        return (new AdminsView($this->container))->getInsert($request, $response, $args);
    }
}
