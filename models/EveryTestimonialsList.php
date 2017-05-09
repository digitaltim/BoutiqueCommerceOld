<?php
declare(strict_types=1);

namespace It_All\BoutiqueCommerce\Models;

class EveryTestimonialsList implements ListViewModel {
    private $testimonials;

    public function __construct(Testimonials $testimonials) {
        $this->testimonials = $testimonials;
    }

    public function getRecords() {
        return $this->testimonials->findAll();
    }
}
