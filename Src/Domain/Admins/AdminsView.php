<?php
declare(strict_types=1);

namespace It_All\BoutiqueCommerce\Src\Domain\Admins;

use It_All\BoutiqueCommerce\Src\Infrastructure\AdminView;
use It_All\BoutiqueCommerce\Src\Infrastructure\UserInterface\FormHelper;

class AdminsView extends AdminView
{
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
}
