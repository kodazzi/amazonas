<?php

namespace Dinnovos\Amazonas\Controllers\Blocks;

use Dinnovos\Amazonas\Main\BundleController;

class AdminController extends BundleController
{
    protected $namespace_model = 'Dinnovos\Amazonas\Models\BlockModel';
    protected $namespace_form = 'Dinnovos\Amazonas\Forms\BlockForm';
    protected $namespace_bundle = 'Dinnovos\Amazonas';
    protected $controller = 'Blocks/Admin';
    protected $view = 'Blocks/Admin';
    protected $title = 'Bloques';
}