<?php

namespace Dinnovos\Site\Controllers\Admin;

use Dinnovos\Site\Main\BundleController;

class WelcomeController extends BundleController
{
    public function indexAction()
    {
            return $this->render('Dinnovos\Site:Welcome:index', array(
        ));
	}
}