<?php
declare(strict_types=1);

namespace It_All\BoutiqueCommerce\Src\Infrastructure\Security\Authentication;

use It_All\BoutiqueCommerce\Src\Infrastructure\Controller;

class AuthenticationController extends Controller
{
    function postLogin($request, $response, $args)
    {
        $_SESSION['formInput'] = $request->getParsedBody();

        if (!$this->validator->validate(
                $request->getParsedBody(),
                $this->authentication->getLoginFieldsValidationRules())
        ) {
            // redisplay the form with input values and error(s)
            $av = new AuthenticationView($this->container);
            return $av->getLogin($request, $response, $args);
        }

        $authentication = $this->authentication->attemptLogin(
            $request->getParam('username'),
            $request->getParam('password')
        );

        if ($authentication) {
            unset($_SESSION['formInput']);
            $this->logger->addInfo($request->getParam('username').' logged in');

            // redirect to proper resource
            if (isset($_SESSION['gotoAdminPage'])) {
                $redirect = $_SESSION['gotoAdminPage'];
                unset($_SESSION['gotoAdminPage']);
            } else {
                $redirect = $this->router->pathFor('admin.home');
            }

            return $response->withRedirect($redirect);
        }

        $this->logger->addWarning('Unsuccessful login for username: '.
            $request->getParam('username') . ' from IP: '. $_SERVER['REMOTE_ADDR']);

        if ($this->authentication->tooManyFailedLogins()) {
            $this->logger->addWarning(
                $this->authentication->getNumFailedLogins() . ' unsuccessful login attempts for IP: '
                . $_SERVER['REMOTE_ADDR']);
            return $response->withRedirect($this->router->pathFor('home'));
        }

        // redisplay the form with input values and error(s)
        $_SESSION['generalFormError'] = 'Login Unsuccessful';
        return $response->withRedirect($this->router->pathFor('authentication.login'));
    }

    public function getLogout($request, $response)
    {
        $this->logger->addInfo($_SESSION['user']['username'].' logged out');
        $this->authentication->logout();
        return $response->withRedirect($this->router->pathFor('home'));
    }
}
