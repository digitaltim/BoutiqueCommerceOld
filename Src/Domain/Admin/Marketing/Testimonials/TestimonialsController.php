<?php
declare(strict_types=1);

namespace It_All\BoutiqueCommerce\Src\Domain\Admin\Marketing\Testimonials;

use It_All\BoutiqueCommerce\Src\Infrastructure\Controller;

class TestimonialsController extends Controller
{
    public function putUpdate($request, $response, $args)
    {
        $testimonialsModel = new TestimonialsModel();
        $this->setFormInput($request, $testimonialsModel);

        if (!$updateResponse = $this->update($request, $response, intval($args['primaryKey']), 'testimonials.update', $testimonialsModel, 'testimonials.index')) {
            // redisplay form with errors and input values
            return (new TestimonialsView($this->container))->getUpdate($request, $response, $args);
        } else {
            return $updateResponse;
        }
    }

    public function postInsert($request, $response, $args)
    {
        $testimonialsModel = new TestimonialsModel();
        $this->setFormInput($request, $testimonialsModel);

        if (!$this->insert('testimonials.insert', $testimonialsModel)) {
            // redisplay form with errors and input values
            return (new TestimonialsView($this->container))->getInsert($request, $response, $args);

        } else {
            return $response->withRedirect($this->router->pathFor('testimonials.index'));
        }
    }

    public function getDelete($request, $response, $args)
    {
        $id = intval($args['primaryKey']);

        $testimonialsModel = new TestimonialsModel();

        return $this->delete($response, $id, 'testimonials.delete', $testimonialsModel, 'testimonials.index', 'id', 'person');
    }
}
