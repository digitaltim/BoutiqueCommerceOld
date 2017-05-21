<?php
declare(strict_types=1);

namespace It_All\BoutiqueCommerce\UI\Views\Admin;

use It_All\BoutiqueCommerce\UI\Views\FormHelper;

class AuthenticationView extends AdminView
{
    public function getLogout($request, $response)
    {
        $this->authentication->logout();
        return $response->withRedirect($this->router->pathFor('home'));
    }

    public function getLogin($request, $response, $args)
    {
        if (isset($_SESSION['numFailedLogins']) && $_SESSION['numFailedLogins'] > 10) {
            die ('Too many attempts.');
        }

        $fields = $this->authentication->getLoginFields();
        $fields = FormHelper::insertValuesErrors($fields, ['password']);

        // render page
        return $this->view->render(
            $response,
            'admin/authentication/login.twig',
            [
                'title' => '::Login',
                'formFields' => $fields,
                'generalFormError' => FormHelper::getGeneralFormError()
            ]
        );
    }
}
