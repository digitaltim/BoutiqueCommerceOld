<?php
declare(strict_types=1);

namespace It_All\BoutiqueCommerce\Src\Domain\Admin\Marketing\AdCodes;

use It_All\BoutiqueCommerce\Src\Infrastructure\AdminView;

class AdCodesView extends AdminView
{

    public function index($request, $response, $args)
    {
        $this->indexView($response, new AdCodesModel, 'adCodes');
    }

    public function getInsert($request, $response, $args)
    {
        return $this->insertView($response, new AdCodesModel(), 'adCodes');
    }

    public function getUpdate($request, $response, $args)
    {
        return $this->updateView($request, $response, $args, new AdCodesModel(), 'adCodes');
    }
}
