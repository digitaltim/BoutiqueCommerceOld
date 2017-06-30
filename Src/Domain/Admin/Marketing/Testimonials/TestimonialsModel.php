<?php
declare(strict_types=1);

namespace It_All\BoutiqueCommerce\Src\Domain\Admin\Marketing\Testimonials;

use It_All\BoutiqueCommerce\Src\Infrastructure\Database\DatabaseTableModel;

class TestimonialsModel extends DatabaseTableModel
{
    public function __construct()
    {
        parent::__construct('testimonials');
    }
}
