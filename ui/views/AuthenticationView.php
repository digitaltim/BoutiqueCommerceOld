<?php
declare(strict_types=1);

namespace It_All\BoutiqueCommerce\UI\Views;

use Slim\Container;

class AuthenticationView
{
    protected $container; // dependency injection container

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    public function __get($name)
    {
        return $this->container->{$name};
    }

    public function getSignOut($request, $response)
    {
        $this->auth->logout();
        return $response->withRedirect($this->router->pathFor('home'));
    }

    public function getSignIn($reqest, $response, $args)
    {
        return $this->view->render($response, 'admin/auth/auth.twig', ['title' => 'Sign In']);
    }

    public function getSignUp($request, $response)
    {
        return $this->view->render($response, 'admin/auth/signup.twig', ['title' => 'Sign Up']);
    }
}
