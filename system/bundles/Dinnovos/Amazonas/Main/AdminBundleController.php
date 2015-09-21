<?php

namespace Dinnovos\Amazonas\Main;

use Kodazzi\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;

class AdminBundleController extends Controller
{
    protected $namespace_model = '';
    protected $namespace_model_translation = '';
    protected $namespace_form = '';
    protected $namespace_bundle = '';
    protected $controller = '';
    protected $action = '';
    protected $view = '';
    protected $title = '';
    protected $fields = 'a.*, b.title';
    protected $breadcrumb = 'Aqu&iacute;';
    protected $default_route = '@default-admin';

    public function preAction()
    {
        $this->action = $this->getParameters('action');

        $UserCard = $this->getUserCardManager()->getCard();

        $this->getView()->set(array(
            'here_breadcrumb'   => $this->breadcrumb,
            'level_breadcrumb'  => $this->buildUrl($this->default_route, array('controller'=> $this->controller, 'action'=>'list')),
            'view_title'        => $this->title,
            'view_controller'   => $this->controller,
            'user_id'           => $UserCard->getAttribute('id'),
            'username'          => $UserCard->getAttribute('username'),
            'first_name'        => $UserCard->getAttribute('first_name'),
            'last_name'         => $UserCard->getAttribute('last_name'),
            'role'              => $UserCard->getRole(),
            'default_route'     => $this->default_route
        ));
    }

    public function listAction()
    {
        if($this->namespace_model_translation)
        {
            $items = $this->getDB()->model($this->namespace_model)->getTranslation('es')->fetchAll(array(), $this->fields);
        }
        else
        {
            $items = $this->getDB()->model($this->namespace_model)->fetchAll();
        }

        return $this->render("{$this->namespace_bundle}:{$this->view}:{$this->action}", array(
            'items'=> $items
        ));
    }

    public function newAction()
    {
        $Form = $this->getForm($this->namespace_form);

        if($this->namespace_model_translation)
        {
            $Form->mergeTranslation();
        }

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

        if($this->namespace_model_translation)
        {
            $Item = $this->getDB()->model($this->namespace_model)->fetchWithTranslation(null, array('a.id' => $id));
        }
        else
        {
            $Item = $this->getDB()->model($this->namespace_model)->fetch(array('a.id' => $id));
        }

        $Form = $this->getForm($this->namespace_form, $Item);

        if($this->namespace_model_translation)
        {
            $Form->mergeTranslation();
        }

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
            if($this->namespace_model_translation)
            {
                \Service::get('db')->model($this->namespace_model_translation)->delete(array('translatable_id'=>$id));
            }

            \Service::get('db')->model($this->namespace_model)->delete(array('id'=>$id));

            return $this->redirectResponse( $this->buildUrl($this->default_route, array('bundle'=>'pages', 'controller'=> $this->controller, 'action'=>'list')) );
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
                    return $this->redirectResponse( $this->buildUrl($this->default_route, array('controller'=> $this->controller, 'action'=>'list')) );
                }
            }

            $View->msgError('Ocurrio un error, por favor verifique el formulario');
        }

        $View->set(array(
            'form' =>$Form
        ));
    }
}