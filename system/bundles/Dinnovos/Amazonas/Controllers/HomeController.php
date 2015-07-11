<?php

namespace Dinnovos\Amazonas\Controllers;

use Dinnovos\Site\Main\BundleController;

class HomeController extends BundleController
{
    public function indexAction()
    {
        return $this->render('@web\Dinnovos\Amazonas:Home:index');
    }
}