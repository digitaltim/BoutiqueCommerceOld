<?php
declare(strict_types=1);

namespace It_All\BoutiqueCommerce\Src\Domain\Admin\Marketing\Testimonials;

use It_All\BoutiqueCommerce\Src\Infrastructure\DatabaseTableModel;

class TestimonialsModel extends DatabaseTableModel
{
    public function __construct()
    {
        parent::__construct('testimonials');
    }

    protected function setColumns()
    {
        $this->columns = [

            'id' => [
                'type' => 'bigint',
                'validation' => ['required' => true]
            ],

            'enter_date' => [
                'type' => 'date',
                'validation' => ['required' => true]
            ],

            'text' => [
                'type' => 'text',
                'validation' => ['required' => true]
            ],

            'person' => [
                'type' => 'charcter varying',
                'max' => 50,
                'validation' => ['required' => true, 'alphaspace' => true]
            ],

            'place' => [
                'type' => 'charcter varying',
                'max' => 100,
                'validation' => ['required' => true, 'alphaspace' => true]
            ],

            'status' => [
                'type' => 'enum',
                'options' => ['active', 'inactive'],
                'validation' => ['required' => true]
            ]
        ];
    }
}
