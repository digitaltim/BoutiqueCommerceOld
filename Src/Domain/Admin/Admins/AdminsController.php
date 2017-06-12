<?php
declare(strict_types=1);

namespace It_All\BoutiqueCommerce\Src\Domain\Admin\Admins;

use It_All\BoutiqueCommerce\Src\Infrastructure\Controller;

class AdminsController extends Controller
{

    public function putUpdate($request, $response, $args)
    {
        $adminsModel = new AdminsModel();
        $this->setFormInput($request, $adminsModel);

        if (!$updateResponse = $this->update($request, $response, intval($args['primaryKey']), 'admins.update', $adminsModel, 'admins.index')) {
            // redisplay form with errors and input values
            return (new AdminsView($this->container))->getUpdate($request, $response, $args);
        } else {
            return $updateResponse;
        }
    }

    public function postInsert($request, $response, $args)
    {
        $adminsModel = new AdminsModel();
        $this->setFormInput($request, $adminsModel);

        // custom validation
        if ($adminsModel->checkRecordExistsForUsername($_SESSION['formInput']['username'])) {
            $_SESSION['generalFormError'] = 'Username already exists';
            $error = true;

        } elseif (!$this->insert('admins.insert', $adminsModel, 'id', true)) {
            $error = true;

        } else {
            return $response->withRedirect($this->router->pathFor('admins.index'));
        }

        if ($error) {
            // redisplay form with errors and input values
            return (new AdminsView($this->container))->getInsert($request, $response, $args);
        }
    }

    public function getDelete($request, $response, $args)
    {
        $id = intval($args['primaryKey']);

        // make sure the current admin is not deleting themself
        if ($id == $this->container->authentication->user()['id']) {
            throw new \Exception('You cannot delete yourself from admins');
        }

        $adminsModel = new AdminsModel();

        return $this->delete($response, $id, 'admins.delete', $adminsModel, 'admins.index', 'id', 'username', true);
    }
}
