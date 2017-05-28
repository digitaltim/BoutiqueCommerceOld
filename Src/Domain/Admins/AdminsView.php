<?php
declare(strict_types=1);

namespace It_All\BoutiqueCommerce\Src\Domain\Admins;

use It_All\BoutiqueCommerce\Src\Infrastructure\AdminView;
use It_All\BoutiqueCommerce\Src\Infrastructure\UserInterface\FormHelper;

class AdminsView extends AdminView
{
    public function show($request, $response, $args)
    {
        $res = (new AdminsModel)->select('id, name, username, role');
        $rows = [];
        while ($row = pg_fetch_assoc($res)) {
            $rows[] = $row;
        }
        $results = $rows;

        return $this->view->render(
            $response,
            'admin/list.twig',
            [
                'title' => 'Admins',
                'insertLink' => ['text' => 'Insert Admin', 'route' => 'admins.insert'],
                'updateRoute' => 'admins.post.update',
                'results' => $results,
                'navigationItems' => $this->navigationItems
            ]
        );
    }

    public function getInsert($request, $response, $args)
    {
        $fields = (new AdminsModel)->getFormFields();

        return $this->view->render(
            $response,
            'admin/form.twig',
            [
                'title' => 'Insert Admin',
                'formActionRoute' => 'admins.post.insert',
                'formFields' => FormHelper::insertValuesErrors($fields),
                'generalFormError' => FormHelper::getGeneralFormError(),
                'navigationItems' => $this->navigationItems
            ]
        );
    }

    public function getUpdate($request, $response, $args)
    {
        $adminData = (new AdminsModel)->selectForId(intval($args['primaryKey']));

        $fields = (new AdminsModel)->getFormFields();
        $fields = FormHelper::insertValuesErrors($fields, $adminData);

        return $this->view->render(
            $response,
            'admin/form.twig',
            [
                'title' => 'Update Admin',
                'formActionRoute' => 'admins.post.update',
                'primaryKey' => $args['primaryKey'],
                'formFields' => $fields,
                'generalFormError' => FormHelper::getGeneralFormError(),
                'navigationItems' => $this->navigationItems
            ]
        );
    }
}
