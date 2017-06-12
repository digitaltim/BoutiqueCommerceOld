<?php
declare(strict_types=1);

namespace It_All\BoutiqueCommerce\Src\Domain\Admin\Orders;

use It_All\BoutiqueCommerce\Src\Infrastructure\AdminView;
use It_All\BoutiqueCommerce\Src\Infrastructure\UserInterface\FormHelper;

class OrdersView extends AdminView
{
    public function index($request, $response, $args)
    {
        $res = (new OrdersModel)->select('id, username, name, role');

        $insertLink = ($this->authorization->check($this->container->settings['authorization']['orders.insert'])) ? ['text' => 'Insert Order', 'route' => 'orders.insert'] : false;
        return $this->view->render(
            $response,
            'admin/list.twig',
            [
                'title' => 'Orders',
                'primaryKeyColumn' => 'id',
                'insertLink' => $insertLink,
                'updateColumn' => 'username',
                'updatePermitted' => $this->authorization
                    ->check($this->container->settings['authorization']['orders.update']),
                'updateRoute' => 'orders.put.update',
                'addDeleteColumn' => true,
                'deleteRoute' => 'orders.delete',
                'table' => pg_fetch_all($res),
                'navigationItems' => $this->navigationItems
            ]
        );
    }

    private function getPersistPasswords(): bool
    {
        return !isset($_SESSION['validationErrors']['password_hash']) && !isset($_SESSION['validationErrors']['confirm_password_hash']);
    }

    public function getInsert($request, $response, $args)
    {
        $fields = (new OrdersModel)->
            getFormFields('insert', $this->getPersistPasswords());

        return $this->view->render(
            $response,
            'admin/form.twig',
            [
                'title' => 'Insert Admin',
                'formActionRoute' => 'orders.post.insert',
                'formFields' => FormHelper::insertValuesErrors($fields),
                'focusField' => FormHelper::getFocusField(),
                'generalFormError' => FormHelper::getGeneralFormError(),
                'navigationItems' => $this->navigationItems
            ]
        );
    }

    public function getUpdate($request, $response, $args)
    {
        $ordersModel = new OrdersModel();
        $fields = $ordersModel->
            getFormFields('update', $this->getPersistPasswords());

        /**
         * data to send to FormHelper - either from the model or from prior input. Note that when sending null FormHelper defaults to using $_SESSION['formInput']. It's important to send null, not $_SESSION['formInput'], because FormHelper unsets $_SESSION['formInput'] after using it.
         * note, this works for post/put because controller calls this method directly in case of errors instead of redirecting
         */
        if ($request->isGet()) {
            $fieldData = $ordersModel->selectForPrimaryKey(intval($args['primaryKey']));
            $fieldData['password_hash'] = ''; // do not display
        } else {
            $fieldData = null;
        }

        return $this->view->render(
            $response,
            'admin/form.twig',
            [
                'title' => 'Update Admin',
                'formActionRoute' => 'orders.put.update',
                'primaryKey' => $args['primaryKey'],
                'formFields' => FormHelper::insertValuesErrors($fields, $fieldData),
                'focusField' => FormHelper::getFocusField(),
                'generalFormError' => FormHelper::getGeneralFormError(),
                'navigationItems' => $this->navigationItems
            ]
        );
    }
}
