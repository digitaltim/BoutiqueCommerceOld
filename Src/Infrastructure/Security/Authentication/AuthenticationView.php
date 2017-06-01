<?php
declare(strict_types=1);

namespace It_All\BoutiqueCommerce\Src\Infrastructure\Security\Authentication;

use It_All\BoutiqueCommerce\Src\Infrastructure\AdminView;
use It_All\BoutiqueCommerce\Src\Infrastructure\UserInterface\FormHelper;

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

        // render page
        return $this->view->render(
            $response,
            'admin/authentication/login.twig',
            [
                'title' => '::Login',
                'focusField' => $this->authentication->getFocusField(),
                'formFields' => FormHelper::insertValuesErrors($fields),
                'generalFormError' => FormHelper::getGeneralFormError()
            ]
        );
    }
}
