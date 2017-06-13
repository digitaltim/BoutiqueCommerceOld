<?php

namespace It_All\BoutiqueCommerce\Src\Infrastructure;

interface DatabaseTable
{
    public function getFormFields(string $databaseAction = 'insert'): array;
}
