<?php
declare(strict_types=1);

namespace It_All\BoutiqueCommerce\Controllers;

class HomeController extends Controller
{
    public function index($request, $response)
    {
        return $this->view->render($response, 'frontend/home.twig', ['title' => 'BoutiqueCommerce']);
    }
}
