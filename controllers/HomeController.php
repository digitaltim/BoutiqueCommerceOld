<?php
declare(strict_types=1);

namespace It_All\BoutiqueCommerce\Controllers;

class HomeController extends Controller
{
    public function index($request, $response)
    {
        $this->flash->addMessage('global', 'Test flash message');
		return $this->view->render($response, 'frontend/home.twig', ['title' => 'BoutiqueCommerce']);
    }
}
