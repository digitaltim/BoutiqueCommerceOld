<?php
declare(strict_types=1);

namespace It_All\BoutiqueCommerce\Src\Domain\Admin\Marketing\Testimonials;

use It_All\BoutiqueCommerce\Src\Infrastructure\Model;
use It_All\BoutiqueCommerce\Src\Infrastructure\UserInterface\FormHelper;
use It_All\BoutiqueCommerce\Src\Infrastructure\Utilities\ValidationService;
use It_All\BoutiqueCommerce\Src\Infrastructure\Database\Queries\QueryBuilder;
use Psr\Log\InvalidArgumentException;

class TestimonialsModel extends Model
{
    private $statusSelectFieldOptions;

    public function __construct()
    {
        // Set select field options
        $this->statusSelectFieldOptions = [
            '-- select --' => 'disabled',
            'active' => 'active',
            'inactive' => 'inactive'
        ];
        parent::__construct('testimonials');
    }

    protected function setColumns()
    {
        $this->columns = [

            'enter_date' => [
            'tag' => 'input',
                'attributes' => [
                    'name' => 'enter_date',
                    'type' => 'hidden',
                    'value' => date('Y-m-d')
                ]
            ],

            'text' => [
                'tag' => 'textarea',
                'label' => 'Text',
                'validation' => [
                    'required' => null,
                ],
                'attributes' => [
                    'id' => 'text',
                    'name' => 'text',
                    'rows' => '5',
                    'cols' => '60',
                    'value' => ''
                ]
            ],

            'person' => [
                'tag' => 'input',
                'label' => 'Person',
                'validation' => [
                    'required' => null,
                    'alphaspace' => null,
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
                    'required' => null,
                    'alphaspace' => null,
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
                'validation' => ['required' => null],
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
    }

    public function getFormFields(string $formType = 'insert'): array
    {
        if ($formType != 'insert' && $formType != 'update') {
            throw new InvalidArgumentException("formType must be insert or update ".$formType);
        }

        $fields = array_merge($this->columns, ['submit' => FormHelper::getSubmitField()]);

        if ($formType == 'update') {
            // override post method to put
            $fields['_METHOD'] = FormHelper::getPutMethodField();
        }

        return $fields;
    }
}
