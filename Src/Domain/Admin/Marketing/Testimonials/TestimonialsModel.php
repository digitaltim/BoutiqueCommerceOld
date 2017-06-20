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
                'isNullable' => false
            ],

            'enter_date' => [
                'type' => 'date',
                'isNullable' => false
            ],

            'text' => [
                'type' => 'text',
                'isNullable' => false
            ],

            'person' => [
                'type' => 'charcter varying',
                'max' => 50,
                'isNullable' => false,
                'validation' => ['alphaspace' => true]
            ],

            'place' => [
                'type' => 'charcter varying',
                'max' => 100,
                'isNullable' => false,
                'validation' => ['alphaspace' => true]
            ],

            'status' => [
                'type' => 'enum',
                'options' => ['active', 'inactive'],
                'isNullable' => false
            ]
        ];


/*
            '' => [
                'tag' => 'input',
                'label' => 'Person',
                'validation' => [
                    'required' => true,
                    'alphaspace' => true,
                    'maxlength' => 50
                ],
                'attributes' => [
                    'id' => 'person',
                    'name' => 'person',
                    'type' => 'text',
                    'size' => '15',
                    'maxlength' => '50',
                    'value' => ''
                ]
            ],

            'place' => [
                'tag' => 'input',
                'label' => 'Place',
                'validation' => [
                    'required' => true,
                    'maxlength' => 100
                ],
                'attributes' => [
                    'id' => 'place',
                    'name' => 'place',
                    'type' => 'text',
                    'size' => '15',
                    'maxlength' => '100',
                    'value' => ''
                ]
            ],

            'status' => [
                'tag' => 'select',
                'label' => 'Status',
                'validation' => ['required' => true],
                'attributes' => [
                    'id' => 'status',
                    'name' => 'status',
                    'type' => 'select',
                    'value' => ''
                ],
                'options' => $this->statusSelectFieldOptions,
                'selected' => 'disabled'
            ]
        ];
*/
    }
}
