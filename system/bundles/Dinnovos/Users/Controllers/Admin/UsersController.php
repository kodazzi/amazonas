<?php

namespace Dinnovos\Users\Controllers\Admin;

use Dinnovos\Amazonas\Main\AdminBundleController;
use Symfony\Component\HttpFoundation\RedirectResponse;

class UsersController extends AdminBundleController
{
    protected $namespace_model = 'Dinnovos\Users\Models\UserModel';
    protected $namespace_form = 'Dinnovos\Users\Forms\UserForm';
    protected $namespace_bundle = 'Dinnovos\Users';
    protected $controller = 'Admin/Users';
    protected $view = 'Admin/Users';
    protected $title = 'Usuarios';
    protected $default_route = '@default-admin-user';

    public function newAction()
    {
        $Form = $this->getForm($this->namespace_form);

        $Form->getWidget('status')->setHidden(false);
        $Form->getWidget('email_confirm')->setHidden(false);

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

        $Form->getWidget('status')->setHidden(false);
        $Form->getWidget('email_confirm')->setHidden(false);

        $result = $this->saveForm($Form, $Item);

        if ( ($result instanceof RedirectResponse) )
        {
            return $result;
        }

        return $this->render("{$this->namespace_bundle}:{$this->view}:{$this->action}",array(
            'item' => $Item
        ));
    }

    protected  function saveForm(\Kodazzi\Form\FormBuilder $Form, $Item = null)
    {
        $View = $this->getView();
        $post = $this->getRequest()->request->all();
        $name_form = $Form->getNameForm();

        if($this->isPost() && array_key_exists($name_form, $post))
        {
            $Form->bind($post);

            if($Form->isValid())
            {
                $result = $Form->save();

                if($result)
                {
                    $View->msgSuccess('El registro fue almacenado correctamente.');

                    return $this->redirectResponse( $this->buildUrl($this->default_route, array('controller'=> $this->controller, 'action'=>'list')) );
                }
            }

            $View->msgError('Ocurrio un error, por favor verifique el formulario');
        }

        $View->set(array(
            'form' => $Form
        ));
    }
}