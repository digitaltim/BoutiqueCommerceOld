<?php
declare(strict_types=1);

namespace It_All\BoutiqueCommerce\Src\Infrastructure\Authorization;

use It_All\BoutiqueCommerce\Src\Infrastructure\Middleware;

class AuthorizationMiddleware extends Middleware
{
    private $minimumRole;

    public function __construct($container, string $minimumRole)
    {
        $this->minimumRole = $minimumRole;
        parent::__construct($container);
    }

    public function __invoke($request, $response, $next)
	{
        // check if the user is not signed in
        if (!$this->container->authorization->check($this->minimumRole)) {
            $this->container->flash->addMessage('error', 'Please sign in before doing that.');
            return $response->withRedirect($this->container->router->pathFor('admins.show'));
        }

		$response = $next($request, $response);
		return $response;
	}
}