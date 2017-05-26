<?php
declare(strict_types=1);

namespace It_All\BoutiqueCommerce\Src\Domain\Admins;

use It_All\BoutiqueCommerce\Src\Infrastructure\AdminView;
use It_All\BoutiqueCommerce\Src\Infrastructure\UserInterface\FormHelper;

class AdminsView extends AdminView
{
    public function show($request, $response, $args)
    {
        $res = (new AdminsModel)->select();
        $rows = [];
        while ($row = pg_fetch_assoc($res)) {
            $rows[] = $row;
        }
        $results = $rows;

        return $this->view->render(
            $response,
            'admin/list.twig',
            [
                'title' => '::Insert Admin',
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
            'admin/admins/insert.twig',
            [
                'title' => 'Insert Admin',
                'formFields' => FormHelper::insertValuesErrors($fields),
                'generalFormError' => FormHelper::getGeneralFormError(),
                'navigationItems' => $this->navigationItems
            ]
        );
    }

    public function getUpdate($request, $response, $args)
    {
        $rows[] = (new AdminsModel)->getAdminDataForId(intval($args['primaryKey']));
        $results = $rows;

        $fields = (new AdminsModel)->getFormFields(); // TODO: create new form that matches DB columns

        foreach ($fields as $fieldName => $fieldInfo) {
            if (isset($results[0][$fieldName])) {
                var_dump($fieldName);
                switch ($fieldInfo['tag']) {
                    case 'textarea':
                        $fields[$fieldName]['value'] = $results[0][$fieldName];
                        break;
                    case 'select':
                        $fields[$fieldName]['selected'] = $results[0][$fieldName];
                        break;
                    default:
                        $fields[$fieldName]['attributes']['value'] = $results[0][$fieldName];
                }
            }
        }

        return $this->view->render(
            $response,
            // 'admin/list.twig',
            'admin/admins/insert.twig', // TODO: create new template 'Modify Admin User'
            [
                'title' => '::Insert Admin',
                'results' => $results,
                'formFields' => $fields,
                'generalFormError' => FormHelper::getGeneralFormError(),
                'navigationItems' => $this->navigationItems,
                'path' => 'show' // TODO: dynamically set the path
            ]
        );
    }

}
