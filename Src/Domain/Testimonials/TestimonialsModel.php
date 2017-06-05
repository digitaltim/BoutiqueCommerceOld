<?php
declare(strict_types=1);

namespace It_All\BoutiqueCommerce\Src\Domain\Testimonials;

use It_All\BoutiqueCommerce\Src\Infrastructure\Utilities\ValidationService;
use It_All\BoutiqueCommerce\Src\Infrastructure\Database\Queries\QueryBuilder;
use Psr\Log\InvalidArgumentException;

class TestimonialsModel
{
    private $status;

    public function __construct()
    {
        $this->status = [
            'active',
            'inactive'
        ];
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function getFormFields(string $formType = 'insert'): array
    {
        if ($formType != 'insert' && $formType != 'update') {
            throw new InvalidArgumentException("formType must be insert or update ".$formType);
        }

        // create status options array based on status property
        // TODO maybe just do this statically in the constructor?
        $statusOptions = [];
        $statusOptions['-- select --'] = 'disabled';
        foreach ($this->status as $status) {
            $statusOptions[$status] = $status;
        }

        $fields = [

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
                'options' => $statusOptions,
                'selected' => 'disabled'
            ],

            'submit' => [
                'tag' => 'input',
                'attributes' => [
                    'type' => 'submit',
                    'name' => 'submit',
                    'value' => 'Go!'
                ]
            ]
        ];


        if ($formType == 'insert') {
        } else { // update
            // override post method
            $fields['_METHOD'] = [
                'tag' => 'input',
                'attributes' => [
                    'type' => 'hidden',
                    'name' => '_METHOD',
                    'value' => 'PUT'
                ]
            ];
        }

        return $fields;
    }

    public function getValidationRules($formType = 'insert'): array
    {
        if ($formType != 'insert' && $formType != 'update') {
            throw new InvalidArgumentException("formType must be insert or update ".$formType);
        }

        return ValidationService::getRules($this->getFormFields($formType));
    }

    public function select(string $columns = '*')
    {
        $q = new QueryBuilder("SELECT $columns FROM testimonials");
        return $q->execute();
    }

    public function insert(string $text, string $person, string $place, string $status)
    {
        $today = date("Y-m-d");

        $q = new QueryBuilder("INSERT INTO testimonials (enter_date, text, person, place, status) VALUES($1, $2, $3, $4, $5)", $today, $text, $person, $place, $status);
        return $q->execute();
    }

    public function update(int $id, string $text, string $person, string $place, string $status)
    {
        $q = new QueryBuilder("UPDATE testimonials SET text = $1, person = $2, place = $3, status = $4", $text, $person, $place, $status);

        $q->add(" WHERE id = $5 RETURNING person", $id);
        return $q->execute();
    }

    public function delete(int $id)
    {
        $q = new QueryBuilder("DELETE FROM testimonials WHERE id = $1 RETURNING person", $id);
        return $q->execute();
    }

    public function selectForId(int $id)
    {
        $q = new QueryBuilder("SELECT * FROM testimonials WHERE id = $1", $id);
        $res = $q->execute();
        return pg_fetch_assoc($res);
    }

    public function recordChanged(int $id, string $text, string $person, string $place, string $password = null): bool
    {
        if (!$record = $this->selectForId($id)) {
            throw new \Exception("No admins record for id $id");
        }

        if ($text != $record['text'] || $person != $record['person'] || $place != $record['place'] || $status != $record['status']) {
            return true;
        }

        return false;
    }
}
