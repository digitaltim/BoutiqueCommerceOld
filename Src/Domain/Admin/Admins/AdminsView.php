<?php
declare(strict_types=1);

namespace It_All\BoutiqueCommerce\Src\Domain\Admin\Admins;

use It_All\BoutiqueCommerce\Src\Infrastructure\AdminView;
use It_All\BoutiqueCommerce\Src\Infrastructure\UserInterface\FormHelper;

class AdminsView extends AdminView
{
    public function index($request, $response, $args)
    {
        $res = (new AdminsModel)->select('id, username, name, role');

        $insertLink = ($this->authorization->check($this->container->settings['authorization']['admins.insert'])) ? ['text' => 'Insert Admin', 'route' => 'admins.insert'] : false;
        return $this->view->render(
            $response,
            'admin/list.twig',
            [
                'title' => 'Admins',
                'primaryKeyColumn' => 'id',
                'insertLink' => $insertLink,
                'updateColumn' => 'username',
                'updatePermitted' => $this->authorization
                    ->check($this->container->settings['authorization']['admins.update']),
                'updateRoute' => 'admins.put.update',
                'addDeleteColumn' => true,
                'deleteRoute' => 'admins.delete',
                'table' => pg_fetch_all($res),
                'navigationItems' => $this->navigationItems
            ]
        );
    }

    private function setPersistPasswords(array &$fields): array
    {
        if (!isset($_SESSION['validationErrors']['password_hash']) && !isset($_SESSION['validationErrors']['confirm_password_hash'])) {
            $fields['password_hash']['persist'] = true;
            $fields['confirm_password_hash']['persist'] = true;
        }
        return $fields;
    }

    public function getInsert($request, $response, $args)
    {
        $fields = (new AdminsModel)->getFormFields();
        $fields = $this->setPersistPasswords($fields);

        return $this->view->render(
            $response,
            'admin/form.twig',
            [
                'title' => 'Insert Admin',
                'formActionRoute' => 'admins.post.insert',
                'formFields' => FormHelper::insertValuesErrors($fields),
                'focusField' => FormHelper::getFocusField(),
                'generalFormError' => FormHelper::getGeneralFormError(),
                'navigationItems' => $this->navigationItems
            ]
        );
    }

    public function getUpdate($request, $response, $args)
    {
        $adminsModel = new AdminsModel();
        $id = intval($args['primaryKey']);
        // make sure there is a record for the model
        if (!$record = $adminsModel->selectForPrimaryKey($id)) {
            $_SESSION['adminNotice'] = [
                "Record $id Not Found",
                'adminNoticeFailure'
            ];
            return $response->withRedirect($this->router->pathFor('admins.index'));
        }

        $fields = (new AdminsModel)->getFormFields('update');
        $fields = $this->setPersistPasswords($fields);

        /**
         * data to send to FormHelper - either from the model or from prior input. Note that when sending null FormHelper defaults to using $_SESSION['formInput']. It's important to send null, not $_SESSION['formInput'], because FormHelper unsets $_SESSION['formInput'] after using it.
         * note, this works for post/put because controller calls this method directly in case of errors instead of redirecting
         */
        if ($request->isGet()) {
            $fieldData = $adminsModel->selectForPrimaryKey($id);
            $fieldData['password_hash'] = ''; // do not display
        } else {
            $fieldData = null;
        }

        return $this->view->render(
            $response,
            'admin/form.twig',
            [
                'title' => 'Update Admin',
                'formActionRoute' => 'admins.put.update',
                'primaryKey' => $args['primaryKey'],
                'formFields' => FormHelper::insertValuesErrors($fields, $fieldData),
                'focusField' => FormHelper::getFocusField(),
                'generalFormError' => FormHelper::getGeneralFormError(),
                'navigationItems' => $this->navigationItems
            ]
        );
    }
}
