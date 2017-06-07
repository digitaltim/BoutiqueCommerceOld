<?php
declare(strict_types=1);

namespace It_All\BoutiqueCommerce\Src\Domain\Admin\Marketing\Testimonials;

use It_All\BoutiqueCommerce\Src\Infrastructure\AdminView;
use It_All\BoutiqueCommerce\Src\Infrastructure\UserInterface\FormHelper;

class TestimonialsView extends AdminView
{
    public function index($request, $response, $args)
    {
        $res = (new TestimonialsModel)->select('id, enter_date, person, text, place, status');

        return $this->view->render(
            $response,
            'admin/list.twig',
            [
                'title' => 'Testimonials',
                'primaryKeyColumn' => 'id',
                'insertLink' => ['text' => 'Insert Testimonial', 'route' => 'testimonials.insert'],
                'updatePermitted' => $this->authorization
                    ->check($this->container->settings['authorization']['testimonials.update']),
                'updateRoute' => 'testimonials.put.update',
                'addDeleteColumn' => true,
                'deleteRoute' => 'testimonials.delete',
                'table' => pg_fetch_all($res),
                'navigationItems' => $this->navigationItems
            ]
        );
    }

    public function getInsert($request, $response, $args)
    {
        $fields = (new TestimonialsModel())->getFormFields('insert');

        return $this->view->render(
            $response,
            'admin/form.twig',
            [
                'title' => 'Insert Testimonial',
                'formActionRoute' => 'testimonials.post.insert',
                'formFields' => FormHelper::insertValuesErrors($fields),
                'focusField' => FormHelper::getFocusField(),
                'generalFormError' => FormHelper::getGeneralFormError(),
                'navigationItems' => $this->navigationItems
            ]
        );
    }

    public function getUpdate($request, $response, $args)
    {
        $testimonialsModel = new TestimonialsModel();
        $fields = $testimonialsModel->getFormFields('update');

        /**
         * data to send to FormHelper - either from the model or from prior input. Note that when sending null FormHelper defaults to using $_SESSION['formInput']. It's important to send null, not $_SESSION['formInput'], because FormHelper unsets $_SESSION['formInput'] after using it.
         * note, this works for post/put because controller calls this method directly in case of errors instead of redirecting
         */
        $fieldData = ($request->isGet()) ? $testimonialsModel->selectForPrimaryKey(intval($args['primaryKey'])) : null;

        return $this->view->render(
            $response,
            'admin/form.twig',
            [
                'title' => 'Update Testimonial',
                'formActionRoute' => 'testimonials.put.update',
                'primaryKey' => $args['primaryKey'],
                'formFields' => FormHelper::insertValuesErrors($fields, $fieldData),
                'focusField' => FormHelper::getFocusField(),
                'generalFormError' => FormHelper::getGeneralFormError(),
                'navigationItems' => $this->navigationItems
            ]
        );
    }
}
