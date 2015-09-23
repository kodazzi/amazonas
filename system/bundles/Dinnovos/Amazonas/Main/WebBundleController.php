<?php

namespace Dinnovos\Amazonas\Main;

use Kodazzi\Controller;

class WebBundleController extends Controller
{
    protected $lang = 'es';

    public function preAction()
    {
        $data = array(
            'title' => $this->getSetting('DS-NAME-PROJECT')
        );

        $this->getView()->set($data);
    }
}