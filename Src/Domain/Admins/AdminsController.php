<?php
declare(strict_types=1);

namespace It_All\BoutiqueCommerce\Src\Domain\Admins;

use It_All\BoutiqueCommerce\Src\Infrastructure\Controller;

class AdminsController extends Controller
{
    function putUpdate($request, $response, $args)
    {
        if (!$this->authorization->checkFunctionality('admins.update')) {
            throw new \Exception('No permission.');
        }

        $_SESSION['formInput'] = $request->getParsedBody();
        $adminsModel = new AdminsModel();

        $error = false;

        if (!$this->validator->validate(
            $request->getParsedBody(),
            $adminsModel->getValidationRules('update'))
        ) {
            $error = true;
        }

        if (!$error) {
            $id = intval($args['primaryKey']);
            $name = $request->getParam('name');
            $username = $request->getParam('username');
            $role = $request->getParam('role');
            // set password to null to send to various model functions ie to test if the record has changed or to update
            $password = ($request->getParam('password') == '') ?
                null : $request->getParam('password');

            // check for no changes made, if so, redirect to list with red notice
            if (!$adminsModel->recordChanged($id, $name, $username, $role, $password)) {
                $_SESSION['adminNotice'] = [
                    "No changes made to admin $username",
                    'adminNoticeFailure'
                ];
                return $response->withRedirect($this->router->pathFor('admins.index'));
            }

            // attempt to update the model
            $res = $adminsModel->update($id, $name, $username, $role, $password);

            if ($res) {
                unset($_SESSION['formInput']);
                $message = 'Admin ' . $username . ' updated';
                $this->logger->addInfo($message);
                $_SESSION['adminNotice'] = [$message, 'adminNoticeSuccess'];

                return $response->withRedirect($this->router->pathFor('admins.index'));
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
        if (!$this->authorization->checkFunctionality('admins.insert')) {
            throw new \Exception('No permission.');
        }

        $_SESSION['formInput'] = $request->getParsedBody();
        $adminsModel = new AdminsModel();

        $error = false;

        if (!$this->validator->validate(
            $request->getParsedBody(),
            $adminsModel->getValidationRules())
        ) {
            $error = true;
        } elseif ($adminsModel->checkRecordExistsForUsername($request->getParam('username'))) {
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
                $message = 'Admin ' . $username . ' inserted';
                $settings = $this->container->get('settings');
                $this->mailer->send($_SERVER['SERVER_NAME'] . " Event", $message, [$settings['emails']['owner']]);
                $this->logger->addInfo($message);
                $_SESSION['adminNotice'] = [$message, 'adminNoticeSuccess'];

                return $response->withRedirect($this->router->pathFor('admins.index'));
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

    function getDelete($request, $response, $args)
    {
        if (!$this->authorization->checkFunctionality('admins.delete')) {
            throw new \Exception('No permission.');
        }
        // make sure the current admin is not deleting themself
        if ($args['primaryKey'] == $this->container->authentication->user()['id']) {
            throw new \Exception('You cannot delete yourself from admins');
        }

        $adminsModel = new AdminsModel();

        if ($res = $adminsModel->delete(intval($args['primaryKey']))) {
            $returned = pg_fetch_all($res);
            $message = 'Admin '.$returned[0]['username'].' deleted';
            $this->logger->addInfo($message);
            $settings = $this->container->get('settings');
            $this->mailer->send($_SERVER['SERVER_NAME'] . " Event", $message, [$settings['emails']['owner']]);
            $_SESSION['adminNotice'] = [$message, 'adminNoticeSuccess'];

            return $response->withRedirect($this->router->pathFor('admins.index'));

        } else {

            $this->logger->addWarning("admins.id: " . $args['primaryKey'] . " not found for deletion. IP: " . $_SERVER['REMOTE_ADDR']);

            $settings = $this->container->get('settings');
            $this->mailer->send($_SERVER['SERVER_NAME'] . " Event", "admins id not found for deletion. Check events log for details.", [$settings['emails']['programmer']]);

            $_SESSION['adminNotice'] = ['Admin not found', 'adminNoticeFailure'];

            return $response->withRedirect($this->router->pathFor('admins.index'));
        }
    }
}
