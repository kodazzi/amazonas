<?php

namespace Dinnovos\Amazonas\Controllers\Admin;

use Dinnovos\Amazonas\Main\MainBundleController;

class CarouselsController extends MainBundleController
{
    protected $namespace_model = 'Dinnovos\Amazonas\Models\CarouselModel';
    protected $namespace_form = 'Dinnovos\Amazonas\Forms\CarouselForm';
    protected $namespace_bundle = 'Dinnovos\Amazonas';
    protected $controller = 'Admin/Carousels';
    protected $view = 'Admin/Carousels';
    protected $title = 'Carrusel';
}