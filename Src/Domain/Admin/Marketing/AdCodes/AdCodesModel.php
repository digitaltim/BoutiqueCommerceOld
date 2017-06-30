<?php
declare(strict_types=1);

namespace It_All\BoutiqueCommerce\Src\Domain\Admin\Marketing\AdCodes;

use It_All\BoutiqueCommerce\Src\Infrastructure\Database\DatabaseTableModel;

class AdCodesModel extends DatabaseTableModel
{
    public function __construct()
    {
        parent::__construct('ad_codes');
    }
}
