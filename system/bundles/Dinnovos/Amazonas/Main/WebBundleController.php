<?php

namespace Dinnovos\Amazonas\Main;

use Kodazzi\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;

class WebBundleController extends Controller
{
    public function preAction()
    {
        $data = array(
            'title' => $this->getSetting('DS-NAME-PROYECT')
        );

        $this->getView()->set($data);
    }
}