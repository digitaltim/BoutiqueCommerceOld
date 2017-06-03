<?php
declare(strict_types=1);

namespace It_All\BoutiqueCommerce\Src\Infrastructure\Security\Authentication;

use It_All\BoutiqueCommerce\Src\Infrastructure\AdminView;
use It_All\BoutiqueCommerce\Src\Infrastructure\UserInterface\FormHelper;

class AuthenticationView extends AdminView
{
    public function getLogin($request, $response, $args)
    {
        if ($this->authentication->tooManyFailedLogins()) {
            return $response->withRedirect($this->router->pathFor('home'));
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
