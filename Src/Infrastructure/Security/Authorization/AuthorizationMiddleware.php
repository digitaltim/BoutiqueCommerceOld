<?php
declare(strict_types=1);

namespace It_All\BoutiqueCommerce\Src\Infrastructure\Security\Authorization;

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
        // check if the user is not authorized
        if (!$this->container->authorization->check($this->minimumRole)) {

            $this->container->logger->addWarning('No authorization for: ' .
                $request->getUri()->getPath() . ' for user: ' . $_SESSION['user']['username']);

            $_SESSION['adminNotice'] = ['No permission', 'adminNoticeFailure'];

            return $response->withRedirect($this->container->router->pathFor('admin.home'));
        }

		$response = $next($request, $response);
		return $response;
	}
}