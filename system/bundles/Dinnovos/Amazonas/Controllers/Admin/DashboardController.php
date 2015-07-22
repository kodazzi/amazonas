<?php

namespace Dinnovos\Amazonas\Controllers\Admin;

use Dinnovos\Amazonas\Main\MainBundleController;

class DashboardController extends MainBundleController
{
    public function preAction()
    {
        $UserCard = $this->getUserCardManager()->get();

        $this->getView()->set(array(
            'username'          => $UserCard->getAttribute('username'),
            'first_name'        => $UserCard->getAttribute('first_name'),
            'last_name'         => $UserCard->getAttribute('last_name'),
            'role'              => $UserCard->getRole(),
            'last_logging'      => $UserCard->getAttribute('last_logging'),
            'default_route'     => $this->default_route
        ));
    }

    public function indexAction()
    {
        return $this->render('Dinnovos\Amazonas:Admin/Dashboard:index', array(

        ));
	}
}