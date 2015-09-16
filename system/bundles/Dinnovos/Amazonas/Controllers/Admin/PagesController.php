<?php

namespace Dinnovos\Amazonas\Controllers\Admin;

use Dinnovos\Amazonas\Main\AdminBundleController;

class PagesController extends AdminBundleController
{
    protected $namespace_model = 'Dinnovos\Amazonas\Models\PageModel';
    protected $namespace_model_translation = 'Dinnovos\Amazonas\Models\PageTranslationModel';
    protected $namespace_form = 'Dinnovos\Amazonas\Forms\PageForm';
    protected $namespace_bundle = 'Dinnovos\Amazonas';
    protected $controller = 'Admin/Pages';
    protected $view = 'Admin/Pages';
    protected $title = 'P&aacute;ginas';

    protected  function saveForm(\Kodazzi\Form\FormBuilder $Form)
    {
        $View = $this->getView();
        $post = $this->getRequest()->request->all();
        $name_form = $Form->getNameForm();

        if($this->isPost() && array_key_exists($name_form, $post))
        {
            $title = (array_key_exists('title', $post[$name_form]) && $post[$name_form]['title'] != '') ? $post[$name_form]['title']:'';
            $code = (array_key_exists('code', $post[$name_form]) && $post[$name_form]['code'] != '') ? $post[$name_form]['code']:'';
            $slug = (array_key_exists('slug', $post[$name_form]) && $post[$name_form]['slug'] != '') ? $post[$name_form]['slug']:'';

            $post[$name_form]['code'] = ($code) ? $this->slug($code) : $this->slug($title);
            $post[$name_form]['slug'] = ($slug) ? $this->slug($slug) : $this->slug($title);

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