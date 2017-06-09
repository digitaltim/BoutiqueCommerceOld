<?php
declare(strict_types=1);

namespace It_All\BoutiqueCommerce\Src\Domain\Admin\Marketing\AdCodes;

use It_All\BoutiqueCommerce\Src\Infrastructure\Model;
use It_All\BoutiqueCommerce\Src\Infrastructure\UserInterface\FormHelper;
use It_All\BoutiqueCommerce\Src\Infrastructure\Utilities\ValidationService;
use It_All\BoutiqueCommerce\Src\Infrastructure\Database\Queries\QueryBuilder;
use Psr\Log\InvalidArgumentException;

class AdCodesModel extends Model
{
    private $statusSelectFieldOptions;

    public function __construct()
    {
        parent::__construct('ad_codes');
    }

    protected function setColumns()
    {
        $this->columns = [

            'start_dt' => [
                'tag' => 'input',
                'attributes' => [
                    'name' => 'start_dt',
                    'type' => 'hidden',
                    'value' => date('Y-m-d')
                ]
            ],

            'end_dt' => [
                'tag' => 'input',
                'attributes' => [
                    'name' => 'end_dt',
                    'type' => 'hidden',
                    'value' => date('Y-m-d')
                ]
            ],

            'description' => [
                'tag' => 'textarea',
                'label' => 'Description',
                'validation' => [
                    'required' => true,
                ],
                'attributes' => [
                    'id' => 'description',
                    'name' => 'description',
                    'rows' => '5',
                    'cols' => '60',
                    'value' => ''
                ]
            ],

            'results' => [
                'tag' => 'textarea',
                'label' => 'Results',
                'validation' => [
                    'required' => true,
                ],
                'attributes' => [
                    'id' => 'results',
                    'name' => 'results',
                    'rows' => '5',
                    'cols' => '60',
                    'value' => ''
                ]
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
