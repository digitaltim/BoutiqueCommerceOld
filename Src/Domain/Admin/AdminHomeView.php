<?php
declare(strict_types=1);

namespace It_All\BoutiqueCommerce\Src\Domain\Admin;

use It_All\BoutiqueCommerce\Src\Infrastructure\AdminView;

class AdminHomeView extends AdminView
{
    public function index($request, $response)
    {
        return $this->view->render(
            $response,
            'admin/home.twig',
            ['title' => 'Admin', 'navigationItems' => $this->navigationItems]
        );
    }
}
