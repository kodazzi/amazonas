<?php

namespace Dinnovos\Amazonas\Controllers;

use Dinnovos\Amazonas\Main\WebBundleController;

class HomeController extends WebBundleController
{
    public function indexAction()
    {
        return $this->render('@web\Dinnovos\Amazonas:Home:index');
    }
}