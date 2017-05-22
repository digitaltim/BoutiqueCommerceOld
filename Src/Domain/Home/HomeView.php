<?php
declare(strict_types=1);

namespace It_All\BoutiqueCommerce\Src\Domain\Home;

use It_All\BoutiqueCommerce\Src\Infrastructure\View;

class HomeView extends View
{
    public function index($request, $response)
    {
        return $this->view->render($response, 'frontend/home.twig', ['title' => 'BoutiqueCommerce']);
    }
}
