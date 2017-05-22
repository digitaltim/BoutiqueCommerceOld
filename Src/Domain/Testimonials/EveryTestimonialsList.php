<?php
declare(strict_types=1);

/*
 * Model used to link the domain model to the GUI.
 * https://www.sitepoint.com/community/t/mvc-vs-pac/28490/3
 */

namespace It_All\BoutiqueCommerce\Src\Domain\Testimonials;

use It_All\BoutiqueCommerce\Src\Infrastructure\ListViewModel;

class EveryTestimonialsList implements ListViewModel {
    private $testimonials;

    public function __construct(TestimonialsModel $testimonials) {
        $this->testimonials = $testimonials;
    }

    public function getRecords() {
        return $this->testimonials->findAll();
    }
}
