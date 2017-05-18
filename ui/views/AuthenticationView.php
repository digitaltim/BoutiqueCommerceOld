<?php
declare(strict_types=1);

namespace It_All\BoutiqueCommerce\UI\Views;

use It_All\BoutiqueCommerce\UI\Views\Admin\AdminView;
use It_All\FormFormer\Form;
use Slim\Container;

class AuthenticationView extends AdminView
{
    protected $container; // dependency injection container

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    public function __get($name)
    {
        return $this->container->{$name};
    }

    public function getSignOut($request, $response)
    {
        $this->auth->logout();
        return $response->withRedirect($this->router->pathFor('home'));
    }

    public function getSignIn($request, $response, $args, string $generalErrorMessage = null)
    {
        // check if there are errors from previous form submission
        $errors = $this->newvalidator->getErrors();

        // grab any prior data submitted to persist form data on load
        $fieldValues = (null !== $request->getParsedBody()) ?
            $request->getParsedBody() :
            [];

        // grab specific prior data from form fields by name
        if (isset($fieldValues['username'])) {
            $usernameFieldValue = $fieldValues['username'];
        } else {
            $usernameFieldValue = null;
        }

        // create form
        $formAttributes = array(
            'method' => 'post',
            'action' => $this->router->pathFor('auth.signin'),
            'novalidate' => 'novalidate'
        );

        $form = new Form($formAttributes, 'verbose');

        // set authentication failed general error message
        if (isset($generalErrorMessage)) {
            $form->setCustomErrorMsg($generalErrorMessage);
        }

        // track attempts to login
        $form->field('input', 'hidden')->name('failedAttempts')->value('0');

        // create username field
        $field = $form->field()
            ->label('Username')
            ->name('username')
            ->id('username')
            ->attr('required')
            ->attr('placeholder', 'username')
            ->value($usernameFieldValue);

        // check if there are errors from prior submission for this field and set error message
        if (isset($errors['username'])) {
            $form->setError($field, $errors['username']);
        }

        // create password field
        $field = $form->field('input', 'password')
            ->label('Password')
            ->name('password')
            ->id('password')
            ->attr('required')
            ->attr('placeholder', 'password');

        // check if there are errors from prior submission for this field and set error message
        if (isset($errors['password'])) {
            $form->setError($field, $errors['password']);
        }

        // create submit button
        $submitButtonInfo = array(
            'attributes' => array(
                'name' => 'submit',
                'type' => 'submit',
                'value' => 'Sign In'
            )
        );

        $form->addField('input', $submitButtonInfo['attributes']);

        // render page
        return $this->view->render($response,
            'admin/authentication/authentication.twig',
            ['title' => '::Login',
            'form' => $form->generate()
        ]);
    }

    public function getSignUp($request, $response)
    {
        return $this->view->render($response,
            'admin/authentication/signup.twig',
            ['title' => 'Sign Up']
        );
    }
}
