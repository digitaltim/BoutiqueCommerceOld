<?php
declare(strict_types=1);

namespace It_All\BoutiqueCommerce\Src\Domain\Admins;

use It_All\BoutiqueCommerce\Src\Infrastructure\AdminView;
use It_All\BoutiqueCommerce\Src\Infrastructure\UserInterface\FormHelper;

class AdminsView extends AdminView
{
    public function getInsert($request, $response, $args)
    {
        $model = new AdminsModel();
        $fields = $model->getFields();
        $fields = FormHelper::insertValuesErrors($fields, ['password']);

        // render page
        return $this->view->render(
            $response,
            'admin/admins/insert.twig',
            [
                'title' => '::Insert Admin',
                'formFields' => $fields,
                'generalFormError' => FormHelper::getGeneralFormError()
            ]
        );
    }
}
