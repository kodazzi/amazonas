<?php

namespace Dinnovos\Amazonas\Controllers\Admin;

use Dinnovos\Amazonas\Main\MainBundleController;
use Symfony\Component\HttpFoundation\RedirectResponse;

class CarouselsSlidesController extends MainBundleController
{
    protected $namespace_model = 'Dinnovos\Amazonas\Models\SlideModel';
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
        $Form->getWidget('carousel_id')->setHidden(true)->setValue($id_carousel);
        $Form->getWidget('image')->setPlaceholder('Clic aqu&iacute; para seleccionar una imagen');
        $result = $this->saveFormSlide($Form, $id_carousel);

        if ( ($result instanceof RedirectResponse) )
        {
            return $result;
        }

        if($Carousel)
        {
            $slides = $this->getDB()->model($this->namespace_model)->fetchAll(array(), 't.*', \PDO::FETCH_CLASS, array('sequence'=>'ASC'));
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

        $Carousel = $this->getDB()->model('Dinnovos\Amazonas\Models\CarouselModel')->fetch(array('id'=>$id_carousel));
        $Slide = $this->getDB()->model($this->namespace_model)->fetch(array('id'=>$id_slide));

        if($Carousel && $Slide)
        {
            $this->getDB()->model($this->namespace_model)->delete(array('id'=>$id_slide));

            return $this->redirectResponse( $this->buildUrl('@default-admin', array('controller'=> $this->controller, 'action'=>'add', 'param1'=>$id_carousel)) );
        }
    }

    protected  function saveFormSlide(\Kodazzi\Form\FormBuilder $Form, $id_carousel)
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
                    return $this->redirectResponse( $this->buildUrl('@default-admin', array('controller'=> $this->controller, 'action'=>'add', 'param1'=>$id_carousel)) );
                }
            }

            $View->msgError('Ocurrio un error, por favor verifique el formulario');
        }

        $View->set(array(
            'form' =>$Form
        ));
    }
}