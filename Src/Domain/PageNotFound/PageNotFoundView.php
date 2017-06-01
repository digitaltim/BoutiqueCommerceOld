<?php
declare(strict_types=1);

namespace It_All\BoutiqueCommerce\Src\Domain\PageNotFound;

use It_All\BoutiqueCommerce\Src\Infrastructure\View;

class PageNotFoundView extends View
{
    public function index($request, $response)
    {
        return $this->view->render(
            $response,
            'frontend/pageNotFound.twig',
            ['title' => 'BoutiqueCommerce', 'pageType' => 'public']
        );
    }
}
