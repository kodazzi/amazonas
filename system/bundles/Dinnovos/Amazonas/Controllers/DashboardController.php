<?php

namespace Dinnovos\Amazonas\Controllers;

use Dinnovos\Amazonas\Main\BundleController;

class DashboardController extends BundleController
{
    public function preAction()
    {

    }

    public function indexAction()
    {
        return $this->render('Dinnovos\Amazonas:Dashboard:index', array(

        ));
	}
}