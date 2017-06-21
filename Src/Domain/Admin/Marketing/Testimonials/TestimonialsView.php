<?php
declare(strict_types=1);

namespace It_All\BoutiqueCommerce\Src\Domain\Admin\Marketing\Testimonials;

use It_All\BoutiqueCommerce\Src\Infrastructure\AdminCrudView;
use Slim\Container;

class TestimonialsView extends AdminCrudView
{
    public function __construct(Container $container)
    {
        parent::__construct($container, new TestimonialsModel(), 'testimonials');
    }
}
