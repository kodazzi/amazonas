<?php

namespace Dinnovos\Amazonas\Controllers\Admin;

use Dinnovos\Amazonas\Main\AdminBundleController;

class CarouselsController extends AdminBundleController
{
    protected $namespace_model = 'Dinnovos\Amazonas\Models\CarouselModel';
    protected $namespace_model_translation = 'Dinnovos\Amazonas\Models\CarouselTranslationModel';
    protected $namespace_form = 'Dinnovos\Amazonas\Forms\CarouselForm';
    protected $namespace_bundle = 'Dinnovos\Amazonas';
    protected $controller = 'Admin/Carousels';
    protected $view = 'Admin/Carousels';
    protected $title = 'Carrusel';

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