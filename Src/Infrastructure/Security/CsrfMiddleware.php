<?php
declare(strict_types=1);

namespace It_All\BoutiqueCommerce\Src\Infrastructure\Security;

use It_All\BoutiqueCommerce\Src\Infrastructure\Middleware;

class CsrfMiddleware extends Middleware
{
	public function __invoke($request, $response, $next)
	{
        if (false === $request->getAttribute('csrf_status')) {
            $errorMessage = 'CSRF Check Failure on resource: ' .
                $request->getUri()->getPath() . ' for IP: ' . $_SERVER['REMOTE_ADDR'];
            $this->container->logger->addWarning($errorMessage);
            throw new \Exception($errorMessage);
        }

		$this->container->view->getEnvironment()->addGlobal('csrf', [
			'fields' => '
				<input type="hidden" name="' . $this->container->csrf->getTokenNameKey() . '" value="' . $this->container->csrf->getTokenName() . '">
				<input type="hidden" name="' . $this->container->csrf->getTokenValueKey() . '" value="' . $this->container->csrf->getTokenValue() . '">
			',
		]);

		$response = $next($request, $response);
		return $response;
	}
}
