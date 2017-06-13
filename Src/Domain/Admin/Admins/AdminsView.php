<?php
declare(strict_types=1);

namespace It_All\BoutiqueCommerce\Src\Domain\Admin\Admins;

use It_All\BoutiqueCommerce\Src\Infrastructure\AdminView;
use It_All\BoutiqueCommerce\Src\Infrastructure\UserInterface\FormHelper;

class AdminsView extends AdminView
{
    public function index($request, $response, $args)
    {
        $this->indexView($response, new AdminsModel, 'admins', 'id, username, name, role');
    }

    private function setPersistPasswords(array &$fields): array
    {
        if (!isset($_SESSION['validationErrors']['password_hash']) && !isset($_SESSION['validationErrors']['confirm_password_hash'])) {
            $fields['password_hash']['persist'] = true;
            $fields['confirm_password_hash']['persist'] = true;
        }
        return $fields;
    }

    /**
     * @param $request
     * @param $response
     * @param $args
     * @return mixed
     * note cannot call parent insertView because of persist passwords call
     */
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
        return $this->updateView($request, $response, $args, new AdminsModel(), 'admins');
    }
}