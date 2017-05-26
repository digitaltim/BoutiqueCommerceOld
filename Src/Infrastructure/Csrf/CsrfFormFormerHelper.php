<?php
declare(strict_types=1);

namespace It_All\BoutiqueCommerce\Src\Infrastructure\Csrf;

use It_All\FormFormer\Form;

class CsrfFormFormerHelper
{
    public static function addCsrfFields(Form $form, $request, $container)
    {
        // CSRF token name and value
        $csrfNameKey = $container->csrf->getTokenNameKey();
        $csrfValueKey = $container->csrf->getTokenValueKey();
        $csrfNameValue = $container->csrf->getTokenName();
        $csrfValuevalue = $container->csrf->getTokenValue();

        // Render HTML form which POSTs to /bar with two hidden input fields for the
// name and value:

        $csrfNameKey = [
            'attributes' => [
                'type' => 'hidden',
                'name' => $csrfNameKey,
                'value' => $csrfNameValue
            ]
        ];
        $form->addField('input', $csrfNameKey['attributes']);

        $csrfValueKey = [
            'attributes' => [
                'type' => 'hidden',
                'name' => $csrfValueKey,
                'value' => $csrfValuevalue
            ]
        ];
        $form->addField('input', $csrfValueKey['attributes']);
    }
}
