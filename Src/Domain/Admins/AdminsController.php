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

        $error = false;

        if (!$this->validator->validate(
            $request->getParsedBody(),
            $adminsModel->getValidationRules())
        ) {
            $error = true;
        }

        if (!$error) {
            // attempt to update the model
            $username = $request->getParam('username');

            $res = $adminsModel->update(
                $request->getParam('name'),
                $username,
                $request->getParam('password'),
                $request->getParam('role'),
                intval($args['primaryKey'])
            );

            if ($res) {
                unset($_SESSION['formInput']);
                $message = 'New admin user ' . $username . ' inserted.';
                $this->logger->addInfo($message);
                $this->flash->addMessage('info', $message);

                return $response->withRedirect($this->router->pathFor('admins.show'));
            } else {
                $_SESSION['generalFormError'] = 'Query Failure';
                $error = true;
            }
        }

        if ($error) {
            // redisplay form with errors and input values
            return (new AdminsView($this->container))->getUpdate($request, $response, $args);
        }
    }

    function postInsert($request, $response, $args)
    {
        $_SESSION['formInput'] = $request->getParsedBody();
        $adminsModel = new AdminsModel();

        $error = false;

        if (!$this->validator->validate(
            $request->getParsedBody(),
            $adminsModel->getValidationRules())
        ) {
            $error = true;
        }

        if ($adminsModel->checkRecordExistsForUsername($request->getParam('username'))) {
            $_SESSION['generalFormError'] = 'Username already exists';
            $error = true;
        }

        if (!$error) {
            // attempt insert
            $username = $request->getParam('username');

            $res = $adminsModel->insert(
                $request->getParam('name'),
                $username,
                $request->getParam('password'),
                $request->getParam('role')
            );

            if ($res) {
                unset($_SESSION['formInput']);
                $message = 'New admin user ' . $username . ' inserted.';
                $this->logger->addInfo($message);
                $this->flash->addMessage('info', $message);

                return $response->withRedirect($this->router->pathFor('admins.show'));
            } else {
                $_SESSION['generalFormError'] = 'Query Failure';
                $error = true;
            }
        }
        
        if ($error) {
            // redisplay the form with input values and error(s)
            return (new AdminsView($this->container))->getInsert($request, $response, $args);
        }
    }
}
