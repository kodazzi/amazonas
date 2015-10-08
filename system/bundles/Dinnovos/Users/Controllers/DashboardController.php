<?php

namespace Dinnovos\Users\Controllers;

use Dinnovos\Amazonas\Main\WebBundleController;

class DashboardController extends WebBundleController
{
    public function indexAction()
    {
        return $this->render('Dinnovos\Users:Dashboard:index', array(
            'menu' => array('dashboard'=>1),
            'show_menu_user' => true,
        ));
	}
}