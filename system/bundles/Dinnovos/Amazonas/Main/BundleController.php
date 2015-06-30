<?php

namespace Dinnovos\Amazonas\Main;

use Kodazzi\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;

class BundleController extends Controller
{
    protected $namespace_model = '';
    protected $namespace_form = '';
    protected $namespace_bundle = '';
    protected $controller = '';
    protected $action = '';
    protected $view = '';
    protected $title = '';
    protected $breadcrumb = 'Aqu&iacute;';

    public function preAction()
    {
        $this->action = $this->getParameters('action');

        $this->getView()->set(array(
            'here_breadcrumb'   => $this->breadcrumb,
            'level_breadcrumb'  => $this->buildUrl('@default-admin', array('controller'=> $this->controller, 'action'=>'list')),
            'view_title'        => $this->title,
            'view_controller'   => $this->controller
        ));
    }

    public function listAction()
    {
        $items = $this->getDB()->model($this->namespace_model)->fetchAll();

        return $this->render("{$this->namespace_bundle}:{$this->view}:{$this->action}", array(
            'items'=> $items
        ));
    }

    public function newAction()
    {
        $Form = $this->getForm($this->namespace_form);

        $result = $this->saveForm($Form);

        if ( ($result instanceof RedirectResponse) )
        {
            return $result;
        }

        return $this->render("{$this->namespace_bundle}:{$this->view}:{$this->action}",array(

        ));
    }

    public function editAction()
    {
        $id = $this->getParameters('param1');
        $Item = \Service::get('db')->model($this->namespace_model)->fetch(array('id'=>$id));

        $Form = $this->getForm($this->namespace_form, $Item);
        $result = $this->saveForm($Form, $Item);

        if ( ($result instanceof RedirectResponse) )
        {
            return $result;
        }

        return $this->render("{$this->namespace_bundle}:{$this->view}:{$this->action}",array(
            'item' => $Item
        ));
    }

    public function deleteAction()
    {
        $id = $this->getParameters('param1');
        $Item = \Service::get('db')->model($this->namespace_model)->fetch(array('id'=>$id));

        if($Item)
        {
            \Service::get('db')->model($this->namespace_model)->delete(array('id'=>$id));

            return $this->redirectResponse( $this->buildUrl('@default-admin', array('bundle'=>'pages', 'controller'=> $this->controller, 'action'=>'list')) );
        }
    }

    protected  function saveForm(\Kodazzi\Form\FormBuilder $Form)
    {
        $View = $this->getView();

        if($Form->bindRequest($this->getRequest()))
        {
            if($Form->isValid())
            {
                $result = $Form->save();

                if( $result )
                {
                    $View->msgSuccess('El registro fue almacenado correctamente.');
                    return $this->redirectResponse( $this->buildUrl('@default-admin', array('controller'=> $this->controller, 'action'=>'list')) );
                }
            }

            $View->msgError('Ocurrio un error, por favor verifique el formulario');
        }

        $View->set(array(
            'form' =>$Form
        ));
    }
}