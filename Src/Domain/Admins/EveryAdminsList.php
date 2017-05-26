<?php
declare(strict_types=1);

/*
 * Model used to link the domain model to the GUI.
 * https://www.sitepoint.com/community/t/mvc-vs-pac/28490/3
 */

namespace It_All\BoutiqueCommerce\Src\Domain\Admins;

use It_All\BoutiqueCommerce\Src\Infrastructure\ListViewModel;

class EveryAdminsList implements ListViewModel {
    private $admins;

    public function __construct(AdminsModel $admins) {
        $this->admins = $admins;
    }

    public function getRecords() {
        return $this->admins->findAll();
    }
}
