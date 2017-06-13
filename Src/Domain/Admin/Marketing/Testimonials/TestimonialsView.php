<?php
declare(strict_types=1);

namespace It_All\BoutiqueCommerce\Src\Domain\Admin\Marketing\Testimonials;

use It_All\BoutiqueCommerce\Src\Infrastructure\AdminView;
use Slim\Container;

class TestimonialsView extends AdminView
{
    public function __construct(Container $container)
    {
        $this->routePrefix = 'testimonials';
        $this->model = new TestimonialsModel();

        parent::__construct($container);
    }
}
