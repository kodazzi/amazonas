<?php

namespace Dinnovos\Amazonas\Controllers\Carousels;

use Dinnovos\Amazonas\Main\BundleController;

class AdminController extends BundleController
{
    protected $namespace_model = 'Dinnovos\Amazonas\Models\CarouselModel';
    protected $namespace_form = 'Dinnovos\Amazonas\Forms\CarouselForm';
    protected $namespace_bundle = 'Dinnovos\Amazonas';
    protected $controller = 'Carousels/Admin';
    protected $view = 'Carousels/Admin';
    protected $title = 'Carrusel';
}