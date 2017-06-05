<?php
declare(strict_types=1);

namespace It_All\BoutiqueCommerce\Src\Domain\Testimonials;

use It_All\BoutiqueCommerce\Src\Infrastructure\Utilities\ValidationService;
use It_All\BoutiqueCommerce\Src\Infrastructure\Database\Queries\QueryBuilder;
use Psr\Log\InvalidArgumentException;

class TestimonialsModel
{
    private $roles;

    public function __construct()
    {
        $this->roles = [
            'owner',
            'director',
            'manager',
            'shipper',
            'admin',
            'store',
            'bookkeeper'
        ];
    }

    public function getRoles()
    {
        return $this->roles;
    }

    public function getFormFields(string $formType = 'insert'): array
    {
        if ($formType != 'insert' && $formType != 'update') {
            throw new InvalidArgumentException("formType must be insert or update ".$formType);
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

    public function insert()
    {
        $q = new QueryBuilder("INSERT INTO testimonials () VALUES()");
        return $q->execute();
    }

    public function update()
    {
        $q = new QueryBuilder("UPDATE testimonials SET name = $1, username = $2, role = $3", $name, $username, $role);
        $argNum = 4;
        if ($password !== null) {
            $q->add(", password_hash = $$argNum", password_hash($password, PASSWORD_DEFAULT));
            $argNum++;
        }
        $q->add(" WHERE id = $$argNum RETURNING username", $id);
        return $q->execute();
    }

    public function delete(int $id)
    {
        $q = new QueryBuilder("DELETE FROM testimonials WHERE id = $1 RETURNING username", $id);
        return $q->execute();
    }

    public function selectForId(int $id)
    {
        $q = new QueryBuilder("SELECT text FROM testimonials WHERE id = $1", $id);
        $res = $q->execute();
        return pg_fetch_assoc($res);
    }

    // If a null password is passed, the password field is not checked
    public function recordChanged(int $id, string $name, string $username, string $role, string $password = null): bool
    {
        if (!$record = $this->selectForId($id)) {
            throw new \Exception("No admins record for id $id");
        }

        if ($name != $record['name'] || $username != $record['username'] || $role != $record['role']) {
            return true;
        }
        if ($password !== null && password_hash($password, PASSWORD_DEFAULT) != $record['password']) {
            return true;
        }

        return false;
    }
}
