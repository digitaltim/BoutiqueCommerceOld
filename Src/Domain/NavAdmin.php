<?php
declare(strict_types=1);

namespace It_All\BoutiqueCommerce\Src\Domain;
use It_All\BoutiqueCommerce\Src\Infrastructure\Security\Authorization\AuthorizationService;
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
        $this->sections = [
            'Admins' => [
                'minimumPermissions' => $container->authorization->getMinimumPermission('admins.index'),
                'link' => $container->router->pathFor('admins.index'),
                'subSections' => [
                    'Insert' => [
                        'minimumPermissions' => $container->authorization->getMinimumPermission('admins.insert'),
                        'link' => $container->router->pathFor('admins.post.insert'),
                    ],
                    'Update' => [
                        'minimumPermissions' => $container->authorization->getMinimumPermission('admins.update'),
                        'link' => $container->router->pathFor('admins.post.insert'),
                    ]
                ]
            ],
            'Orders' => [
                'minimumPermissions' => 'owner',
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

    private function getSubSectionForUser(AuthorizationService $autho, string $sectionName, string $subSectionName)
    {
        $subSection = $this->sections[$sectionName]['subSections'][$subSectionName];

        // if there are subsection permissions and they are not met
        if (isset($subSection['minimumPermissions']) && !$autho->check($subSection['minimumPermissions'])) {
            return false;
        }

        return $subSection;
    }

    private function getSectionForUser(AuthorizationService $autho, string $sectionName)
    {
        // if there are section permissions and they are not met
        if (isset($this->sections[$sectionName]['minimumPermissions']) && !$autho->check($this->sections[$sectionName]['minimumPermissions'])) {
            return false;
        }

        $updatedSection = $this->sections[$sectionName];

        $subSections = []; // rebuild subsections based on authorization

        // look for subsections
        if (isset($this->sections[$sectionName]['subSections'])) {
            foreach ($this->sections[$sectionName]['subSections'] as $subSectionName => $subSectionInfo) {

                $updatedSubSection = $this->getSubSectionForUser($autho, $sectionName, $subSectionName);
                // CAREFUL, apparently empty arrays evaluate to false
                if ($updatedSubSection !== false) {
                    $subSections[$subSectionName] = $updatedSubSection;
                }
            }
        }

        if (count($subSections) > 0) {
            $updatedSection['subSections'] = $subSections;
        } else {
            unset ($updatedSection['subSections']);

            // if there are no subsections and no top level link, no need to show section
            if (!isset($this->sections[$sectionName]['link'])) {
                return false;
            }
        }

        return $updatedSection;
    }

    public function getSectionsForUser(AuthorizationService $autho)
    {
        $sections = []; // rebuild nav sections based on authorization for this user

        foreach ($this->sections as $sectionName => $sectionInfo) {

            $updatedSection = $this->getSectionForUser($autho, $sectionName);
            // CAREFUL, apparently empty arrays evaluate to false
            if ($updatedSection !== false) {
                $sections[$sectionName] = $updatedSection;
            }
        }

        return $sections;
    }
}
