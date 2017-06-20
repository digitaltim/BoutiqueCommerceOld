<?php
declare(strict_types=1);

namespace It_All\BoutiqueCommerce\Src\Domain\Admin\Marketing\AdCodes;

use It_All\BoutiqueCommerce\Src\Infrastructure\DatabaseTableModel;

class AdCodesModel extends DatabaseTableModel
{
    public function __construct()
    {
        parent::__construct('ad_codes');
    }

    protected function setColumns()
    {
        $this->columns = [

            'id' => [
                'type' => 'bigint',
                'isNullable' => false
            ],

            'start_dt' => [
                'type' => 'timestamp without time zone',
                'isNullable' => false
            ],

            'end_dt' => [
                'type' => 'timestamp without time zone',
                'isNullable' => true
            ],

            'description' => [
                'type' => 'text',
                'isNullable' => false
            ],

            'results' => [
                'type' => 'text',
                'isNullable' => false
            ]
        ];
    }
}
