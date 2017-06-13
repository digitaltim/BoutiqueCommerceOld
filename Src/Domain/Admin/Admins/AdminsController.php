<?php
declare(strict_types=1);

namespace It_All\BoutiqueCommerce\Src\Domain\Admin\Admins;

use It_All\BoutiqueCommerce\Src\Infrastructure\Controller;
use Slim\Container;

class AdminsController extends Controller
{
    public function __construct(Container $container)
    {
        $this->model = new AdminsModel();
        $this->view = new AdminsView($container);
        $this->routePrefix = 'admins';
        parent::__construct($container);
    }

    /**
     * override for custom validation
     * @param $request
     * @param $response
     * @param $args
     * @return mixed
     */
    public function postInsert($request, $response, $args)
    {
        $this->setFormInput($request, $this->model);

        // custom validation
        if ($this->model->checkRecordExistsForUsername($_SESSION['formInput']['username'])) {
            $_SESSION['generalFormError'] = 'Username already exists';
            $error = true;
        } elseif (!$this->insert()) {
            $error = true;
        } else {
            return $response->withRedirect($this->router->pathFor($this->routePrefix.'.index'));
        }

        if ($error) {
            // redisplay form with errors and input values
            return ($this->view->getInsert($request, $response, $args));
        }
    }

    /**
     * overrride for custom validation
     * @param $request
     * @param $response
     * @param $args
     * @return mixed
     * @throws \Exception
     */
    public function getDelete($request, $response, $args)
    {
        // make sure the current admin is not deleting themself
        if (intval($args['primaryKey']) == $this->container->authentication->user()['id']) {
            throw new \Exception('You cannot delete yourself from admins');
        }

        return $this->delete($response, $args,'username', true);
    }
}
