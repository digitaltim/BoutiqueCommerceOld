<?php
declare(strict_types=1);

namespace It_All\BoutiqueCommerce\Src\Domain\Admin\Marketing\Testimonials;

use It_All\BoutiqueCommerce\Src\Infrastructure\AdminView;

class TestimonialsView extends AdminView
{

    public function index($request, $response, $args)
    {
        $this->indexView($response, new TestimonialsModel, 'testimonials');
    }

    public function getInsert($request, $response, $args)
    {
        return $this->insertView($response, new TestimonialsModel(), 'testimonials');
    }

    public function getUpdate($request, $response, $args)
    {
        return $this->updateView($request, $response, $args, new TestimonialsModel(), 'testimonials');
    }
}
