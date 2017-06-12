<?php
declare(strict_types=1);

namespace It_All\BoutiqueCommerce\Src\Domain\Admin\Marketing\AdCodes;

use It_All\BoutiqueCommerce\Src\Infrastructure\Controller;

class AdCodesController extends Controller
{
    public function putUpdate($request, $response, $args)
    {
        $adCodesModel = new AdCodesModel();
        $this->setFormInput($request, $adCodesModel);

        if (!$updateResponse = $this->update($request, $response, intval($args['primaryKey']), 'adCodes.update', $adCodesModel, 'adCodes.index')) {
            // redisplay form with errors and input values
            return (new AdCodesView($this->container))->getUpdate($request, $response, $args);
        } else {
            return $updateResponse;
        }
    }

    public function postInsert($request, $response, $args)
    {
        $adCodesModel = new AdCodesModel();
        $this->setFormInput($request, $adCodesModel);

        if (!$this->insert('adCodes.insert', $adCodesModel)) {
            // redisplay form with errors and input values
            return (new AdCodesView($this->container))->getInsert($request, $response, $args);

        } else {
            return $response->withRedirect($this->router->pathFor('adCodes.index'));
        }
    }

    public function getDelete($request, $response, $args)
    {
        $id = intval($args['primaryKey']);

        $adCodesModel = new AdCodesModel();

        return $this->delete($response, $id, 'adCodes.delete', $adCodesModel, 'adCodes.index', 'id', 'description');
    }
}
