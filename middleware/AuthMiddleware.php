<?php
declare(strict_types=1);

namespace It_All\BoutiqueCommerce\Middleware;

/**
* 
*/
class AuthMiddleware extends Middleware
{
	public function __invoke($request, $response, $next)
	{
		// check if the user is not signed in
		if (!$this->container->auth->check()) {
			$this->container->flash->addMessage('error', 'Please sign in before doing that.');
			return $response->withRedirect($this->container->router->pathFor('auth.signin'));
		}
		
		$response = $next($request, $response);
		return $response;
	}
}