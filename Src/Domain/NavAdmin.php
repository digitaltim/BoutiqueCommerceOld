<?php
declare(strict_types=1);

namespace It_All\BoutiqueCommerce\Src\Domain;
use Slim\Container;

/**
 * navigation for admin pages
 */
class NavAdmin
{
    private $sections;

    function __construct(Container $container)
    {
        $this->setSections($container);
        return $this;
    }

    private function setSections(Container $container)
    {
        global $config;

        $this->sections = [
            'Admins' => [
                'link' => $container->router->pathFor('admins.show'),
                'subSection' => [
                    'Insert' => [
                        'link' => $container->router->pathFor('admins.post.insert'),
                    ]
                ]
            ],
            'Orders' => [

            ],
            'Customers' => [

            ],
            'Products' => [

            ],
            'Designers' => [

            ],
            'Marketing' => [

            ]
        ];
    }

    public function getSections()
    {
        return $this->sections;
    }
}
