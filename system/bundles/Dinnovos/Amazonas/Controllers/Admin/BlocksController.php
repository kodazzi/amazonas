<?php

namespace Dinnovos\Amazonas\Controllers\Admin;

use Dinnovos\Amazonas\Main\AdminBundleController;

class BlocksController extends AdminBundleController
{
    protected $namespace_model = 'Dinnovos\Amazonas\Models\BlockModel';
    protected $namespace_model_translation = 'Dinnovos\Amazonas\Models\BlockTranslationModel';
    protected $namespace_form = 'Dinnovos\Amazonas\Forms\BlockForm';
    protected $namespace_bundle = 'Dinnovos\Amazonas';
    protected $controller = 'Admin/Blocks';
    protected $view = 'Admin/Blocks';
    protected $title = 'Bloques';

    protected  function saveForm(\Kodazzi\Form\FormBuilder $Form)
    {
        $View = $this->getView();
        $post = $this->getRequest()->request->all();
        $name_form = $Form->getNameForm();

        if($this->isPost() && array_key_exists($name_form, $post))
        {
            $post[$name_form]['label'] = (array_key_exists('label', $post[$name_form]) && $post[$name_form]['label'] != '') ? $this->slug($post[$name_form]['label']):'';

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
            'form' =>$Form
        ));
    }
}