<?php
declare(strict_types=1);

namespace It_All\BoutiqueCommerce\Controllers;

use It_All\BoutiqueCommerce\UI\NavAdmin;

class HomeController extends Controller
{
    public function index($request, $response)
    {
        return $this->view->render($response, 'frontend/home.twig', ['title' => 'BoutiqueCommerce']);
    }

    public function test($request, $response)
    {
        $navAdmin = new NavAdmin();
        $navigationItems = $navAdmin->getSections();

        return $this->view->render($response, 'admin/test.twig', ['navigationItems' => $navigationItems]);
    }
}
