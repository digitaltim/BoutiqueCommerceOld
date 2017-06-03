<?php
declare(strict_types=1);

namespace It_All\BoutiqueCommerce\Src\Infrastructure\Security\Authentication;

use It_All\BoutiqueCommerce\Src\Infrastructure\Middleware;

class AuthenticationMiddleware extends Middleware
{
	public function __invoke($request, $response, $next)
	{
		// check if the user is not signed in
		if (!$this->container->authentication->check()) {
            $this->container->logger->addWarning('Login required to access resource: ' .
                $request->getUri()->getPath() . ' for IP: ' . $_SERVER['REMOTE_ADDR']);
            $_SESSION['adminNotice'] = ["Login required", 'adminNoticeFailure'];
            $_SESSION['gotoAdminPage'] = $request->getUri()->getPath();
            return $response->withRedirect($this->container->router->pathFor('authentication.login'));
		}

		$response = $next($request, $response);
		return $response;
	}
}
