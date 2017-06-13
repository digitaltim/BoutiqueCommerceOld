<?php
declare(strict_types=1);

namespace It_All\BoutiqueCommerce\Src\Domain\Admin\Marketing\Testimonials;

use It_All\BoutiqueCommerce\Src\Infrastructure\Controller;
use Slim\Container;

class TestimonialsController extends Controller
{
    public function __construct(Container $container)
    {
        $this->model = new TestimonialsModel();
        $this->view = new TestimonialsView($container);
        $this->routePrefix = 'testimonials';
        parent::__construct($container);
    }

    /**
     * override for custom return column
     * @param $request
     * @param $response
     * @param $args
     * @return mixed
     */
    public function getDelete($request, $response, $args)
    {
        return $this->delete($response, $args,'person');
    }
}
