<?php
declare(strict_types=1);

namespace It_All\BoutiqueCommerce\Src\Domain\Testimonials;

use It_All\BoutiqueCommerce\Src\Infrastructure\AdminView;
use It_All\BoutiqueCommerce\Src\Infrastructure\UserInterface\FormHelper;

class TestimonialsView extends AdminView
{
    public function index($request, $response, $args)
    {
        $res = (new TestimonialsModel)->select('id, text');
        $results = [];
        while ($row = pg_fetch_assoc($res)) {
            $results[] = array_merge($row, ['delete' => 'testimonials.delete']);
        }

        return $this->view->render(
            $response,
            'admin/list.twig',
            [
                'title' => 'Testimonials',
                'insertLink' => ['text' => 'Insert Testimonial', 'route' => 'testimonials.insert'],
                'updateColumn' => 'id',
                'updateRoute' => 'testimonials.put.update',
                'table' => $results,
                'navigationItems' => $this->navigationItems
            ]
        );
    }

    public function getInsert($request, $response, $args)
    {
        $fields = (new TestimonialsModel())->
            getFormFields('insert');

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
        $adminsModel = new AdminsModel();
        $fields = $adminsModel->
            getFormFields('update', $this->getPersistPasswords());

        /**
         * data to send to FormHelper - either from the model or from prior input. Note that when sending null FormHelper defaults to using $_SESSION['formInput']. It's important to send null, not $_SESSION['formInput'], because FormHelper unsets $_SESSION['formInput'] after using it.
         * note, this works for post/put because controller calls this method directly in case of errors instead of redirecting
         */
        if ($request->isGet()) {
            if (!$fieldData = $adminsModel->selectForId(intval($args['primaryKey']))) {
                throw new \Exception('Invalid primary key for admins: '.$args['primaryKey']);
            }
        } else {
            $fieldData = null;
        }
        $fieldData = ($request->isGet()) ? $adminsModel->selectForId(intval($args['primaryKey'])) : null;

        return $this->view->render(
            $response,
            'admin/form.twig',
            [
                'title' => 'Update Admin',
                'formActionRoute' => 'admins.put.update',
                'primaryKey' => $args['primaryKey'],
                'formFields' => FormHelper::insertValuesErrors($fields, $fieldData),
                'focusField' => FormHelper::getFocusField(),
                'generalFormError' => FormHelper::getGeneralFormError(),
                'navigationItems' => $this->navigationItems
            ]
        );
    }
}
