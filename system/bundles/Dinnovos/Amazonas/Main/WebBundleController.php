<?php

namespace Dinnovos\Amazonas\Main;

use Kodazzi\Controller;

class WebBundleController extends Controller
{
    protected $lang = 'es';
    protected $title = null;
    protected $hasSession = false;
    protected $User = null;

    public function preAction()
    {
        $this->title = ($this->title) ? $this->title : $this->getSetting('DS-NAME-PROJECT');
        $Card = $this->getUserCardManager()->getCard();

        // Busca si existe la sesion.
        if($Card && $Card->getRole() == 'USER')
        {
            $this->hasSession = true;
            $this->User = $Card->getAttributes();
        }

        $this->getView()->set(array(
            'title'         => $this->title,
            'has_session'   => $this->hasSession,
            'user'          => $this->User
        ));
    }
}