<?php
declare(strict_types=1);

namespace It_All\BoutiqueCommerce\Src\Infrastructure\Authentication;

use It_All\BoutiqueCommerce\Src\Domain\Admins\AdminsModel;
use It_All\BoutiqueCommerce\Src\Infrastructure\Utilities\ValidationService;

class AuthenticationService
{
    private $loginFields;

    public function user()
    {
        if (isset($_SESSION['user'])) {
            return $_SESSION['user'];
        }
        return false;
    }

    public function check()
    {
        return isset($_SESSION['user']);
    }

    public function attemptLogin(string $username, string $password): bool
    {
        $admins = new AdminsModel('admins');
        $rs = $admins->getAdminDataForUsername($username);
        $user = pg_fetch_assoc($rs);

        // check if user exists
        if (!$user) {
            $this->failedLogin();
            return false;
        }

        // verify password for that user
        if (password_verify($password, $user['password_hash'])) {
            // set session for user
            $_SESSION['user'] = [
                'id' => $user['admin_id'],
                'username' => $username,
                'role' => $user['role']
            ];
            return true;
        } else {
            $this->failedLogin();
            return false;
        }
    }

    private function failedLogin()
    {
        if (isset($_SESSION['numFailedLogins'])) {
            $_SESSION['numFailedLogins']++;
        } else {
            $_SESSION['numFailedLogins'] = 1;
        }
    }

    public function logout()
    {
        unset($_SESSION['user']);
    }

    private function setLoginFields()
    {
        $this->loginFields = [

            'username' => [
                'tag' => 'input',
                'label' => 'Username',
                'validation' => ['required'],
                'attributes' => [
                    'id' => 'username',
                    'name' => 'username',
                    'type' => 'text',
                    'size' => '15',
                    'maxlength' => '100',
                    'value' => 'temp'
                ]
            ],

            'password' => [
                'tag' => 'input',
                'label' => 'Password',
                'validation' => ['required'],
                'attributes' => [
                    'type' => 'password',
                    'name' => 'password',
                    'size' => '15',
                    'maxlength' => '100',
                ],
                'persist' => false,
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
    }

    public function getLoginFields(): array
    {
        if (!isset($this->loginFields)) {
            $this->setLoginFields();
        }

        return $this->loginFields;
    }

    public function getLoginFieldsValidationRules(): array
    {
        if (!isset($this->loginFields)) {
            $this->setLoginFields();
        }

        return ValidationService::getRules($this->loginFields);
    }
}
