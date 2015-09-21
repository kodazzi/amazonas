<?php

namespace Dinnovos\Amazonas\Controllers\Admin;

use Dinnovos\Amazonas\Main\AdminBundleController;
use Symfony\Component\HttpFoundation\RedirectResponse;

class CarouselsSlidesController extends AdminBundleController
{
    protected $namespace_model = 'Dinnovos\Amazonas\Models\SlideModel';
    protected $namespace_model_translation = 'Dinnovos\Amazonas\Models\SlideTranslationModel';
    protected $namespace_form = 'Dinnovos\Amazonas\Forms\SlideForm';
    protected $namespace_bundle = 'Dinnovos\Amazonas';
    protected $controller = 'Admin/CarouselsSlides';
    protected $view = 'Admin/CarouselsSlides';
    protected $title = 'Carrusel';

    public function addAction()
    {
        $id_carousel = $this->getParameters('param1');
        $slides = array();

        $Carousel = $this->getDB()->model('Dinnovos\Amazonas\Models\CarouselModel')->fetch(array('id'=>$id_carousel));

        $Form = $this->getForm($this->namespace_form);
        $Form->mergeTranslation();
        $Form->getWidget('carousel_id')->setHidden(true)->setValue($id_carousel);

        $result = $this->saveFormSlide($Form, $id_carousel);

        if ( ($result instanceof RedirectResponse) )
        {
            return $result;
        }

        if($Carousel)
        {
            $slides = $this->getDB()->model($this->namespace_model, 'a')->fetchAll(array('a.carousel_id'=>$Carousel->id), 'a.*', \PDO::FETCH_CLASS, array('a.sequence'=>'ASC'));

            foreach($slides as $key => $slide)
            {
                $translations = $this->getDB()->model($this->namespace_model_translation, 'a')->join('Dinnovos\Amazonas\Models\LanguageModel', 'l')->fetchAll(array('a.translatable_id'=>$slide->id), 'a.*, l.code, l.name as language');

                $slide->Translation = $translations;
            }
        }

        return $this->render("{$this->namespace_bundle}:{$this->view}:slides", array(
            'carousel' => $Carousel,
            'slides' => $slides,
            'form' => $Form
        ));
    }

    public function deleteAction()
    {
        $id_carousel = $this->getParameters('param1');
        $id_slide = $this->getParameters('param2');

        $Carousel = $this->getDB()->model('Dinnovos\Amazonas\Models\CarouselModel')->fetch(array('a.id' => $id_carousel));
        $Slide = $this->getDB()->model($this->namespace_model)->fetch(array('id' => $id_slide));

        if($Carousel && $Slide)
        {
            $this->getDB()->model($this->namespace_model_translation)->delete(array('translatable_id'=>$id_slide));
            $this->getDB()->model($this->namespace_model)->delete(array('id'=>$id_slide));

            return $this->redirectResponse( $this->buildUrl($this->default_route, array('controller'=> $this->controller, 'action'=>'add', 'param1'=>$id_carousel)) );
        }
    }

    protected function saveFormSlide(\Kodazzi\Form\FormBuilder $Form, $id_carousel)
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
                    return $this->redirectResponse( $this->buildUrl($this->default_route, array('controller'=> $this->controller, 'action'=>'add', 'param1'=>$id_carousel)) );
                }
            }

            $View->msgError('Ocurrio un error, por favor verifique el formulario');
        }

        $View->set(array(
            'form' =>$Form
        ));
    }
}