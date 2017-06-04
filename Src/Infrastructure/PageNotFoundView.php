<?php
declare(strict_types=1);

namespace It_All\BoutiqueCommerce\Src\Infrastructure;

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
