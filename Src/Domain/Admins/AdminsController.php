<?php
declare(strict_types=1);

namespace It_All\BoutiqueCommerce\Src\Domain\Admins;

use It_All\BoutiqueCommerce\Src\Infrastructure\Controller;
use It_All\BoutiqueCommerce\Src\Infrastructure\Utilities\ValidationService;

class AdminsController extends Controller
{
    public function index($request, $response, $args)
    {
        return $this->view->render($response, 'admin.twig', ['title' => 'test title', 'rows' => $rows]);
    }

    function postInsert($request, $response, $args)
    {
        $_SESSION['formInput'] = $request->getParsedBody();

        $adminsModel = new AdminsModel();

        if (!$this->validator->validate(
            $request->getParsedBody(),
            ValidationService::getRules($adminsModel->getFields()))
        ) {
            // redisplay the form with input values and error(s)
            return $response->withRedirect($this->router->pathFor('admins.insert'));
        }

        if ($adminsModel->checkRecordExistsForUsername($request->getParam('username'))) {
            $_SESSION['generalFormError'] = 'Username already exists';
            // redisplay the form with input values and error(s)
            return $response->withRedirect($this->router->pathFor('admins.insert'));
        }

        $username = $request->getParam('username');

        $res = $adminsModel->insert([
            'username' => $username,
            'password_hash' => password_hash($request->getParam('password'), PASSWORD_DEFAULT),
        ]);

        if ($res) {
            unset($_SESSION['formInput']);
            $message = 'New admin user ' . $username . ' inserted.';
            $this->logger->addInfo($message);
            $this->flash->addMessage('info', $message);

            return $response->withRedirect($this->router->pathFor('crud.show', ['table' => 'admins']));
        }

        //
        $_SESSION['generalFormError'] = 'Query Failure';
        // redisplay the form with input values and error(s)
        return $response->withRedirect($this->router->pathFor('admins.insert'));
    }
}
