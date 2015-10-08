<?php

namespace Dinnovos\Amazonas\Controllers\Admin;

use Dinnovos\Amazonas\Main\AdminBundleController;

class DashboardController extends AdminBundleController
{
    public function preAction()
    {
        $UserCard = $this->getUserCardManager()->getCard();

        $this->getView()->set(array(
            'username'              => $UserCard->getAttribute('username'),
            'first_name'            => $UserCard->getAttribute('first_name'),
            'last_name'             => $UserCard->getAttribute('last_name'),
            'role'                  => $UserCard->getRole(),
            'last_logging'          => $UserCard->getAttribute('last_logging'),
            'default_route'         => $this->default_route,
            'default_route_layout'  => $this->default_route_layout,
            'title'                 => $this->getSetting('DS-NAME-PROJECT')
        ));
    }

    public function indexAction()
    {
        return $this->render('Dinnovos\Amazonas:Admin/Dashboard:index', array(

        ));
	}
}