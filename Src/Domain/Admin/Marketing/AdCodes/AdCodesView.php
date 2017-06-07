<?php
declare(strict_types=1);

namespace It_All\BoutiqueCommerce\Src\Domain\Admin\Marketing\AdCodes;

use It_All\BoutiqueCommerce\Src\Infrastructure\AdminView;
use It_All\BoutiqueCommerce\Src\Infrastructure\UserInterface\FormHelper;

class AdCodesView extends AdminView
{
    public function index($request, $response, $args)
    {
        $res = (new AdCodesModel)->select('id, start_dt, end_dt, description, results');

        return $this->view->render(
            $response,
            'admin/list.twig',
            [
                'title' => 'AdCodes',
                'primaryKeyColumn' => 'id',
                'insertLink' => ['text' => 'Insert Ad Code', 'route' => 'adCodes.insert'],
                'updatePermitted' => $this->authorization
                    ->check($this->container->settings['authorization']['adCodes.update']),
                'updateRoute' => 'adCodes.put.update',
                'addDeleteColumn' => true,
                'deleteRoute' => 'adCodes.delete',
                'table' => pg_fetch_all($res),
                'navigationItems' => $this->navigationItems
            ]
        );
    }

    public function getInsert($request, $response, $args)
    {
        $fields = (new AdCodesModel())->getFormFields('insert');

        return $this->view->render(
            $response,
            'admin/form.twig',
            [
                'title' => 'Insert Ad Code',
                'formActionRoute' => 'adCodes.post.insert',
                'formFields' => FormHelper::insertValuesErrors($fields),
                'focusField' => FormHelper::getFocusField(),
                'generalFormError' => FormHelper::getGeneralFormError(),
                'navigationItems' => $this->navigationItems
            ]
        );
    }

    public function getUpdate($request, $response, $args)
    {
        $adCodesModel = new AdCodesModel();
        $fields = $adCodesModel->getFormFields('update');

        /**
         * data to send to FormHelper - either from the model or from prior input. Note that when sending null FormHelper defaults to using $_SESSION['formInput']. It's important to send null, not $_SESSION['formInput'], because FormHelper unsets $_SESSION['formInput'] after using it.
         * note, this works for post/put because controller calls this method directly in case of errors instead of redirecting
         */
        $fieldData = ($request->isGet()) ? $adCodesModel->selectForPrimaryKey(intval($args['primaryKey'])) : null;

        return $this->view->render(
            $response,
            'admin/form.twig',
            [
                'title' => 'Update Ad Cpde',
                'formActionRoute' => 'adCodes.put.update',
                'primaryKey' => $args['primaryKey'],
                'formFields' => FormHelper::insertValuesErrors($fields, $fieldData),
                'focusField' => FormHelper::getFocusField(),
                'generalFormError' => FormHelper::getGeneralFormError(),
                'navigationItems' => $this->navigationItems
            ]
        );
    }
}
