<?php

namespace Dinnovos\Amazonas\Controllers;

use Dinnovos\Amazonas\Main\WebBundleController;

class PagesController extends WebBundleController
{
    public function showAction()
    {
        $slug = $this->getParameters('slug');

        $Page = $this->getDB()->model('Dinnovos\Amazonas\Models\PageModel')->getTranslation($this->lang)->fetch(array('b.slug'=>$slug));

        if(!$Page)
        {
            return $this->responseError404();
        }

        return $this->render('@web\Dinnovos\Amazonas:Pages:show', array(
            'page' => $Page
        ));
    }
}