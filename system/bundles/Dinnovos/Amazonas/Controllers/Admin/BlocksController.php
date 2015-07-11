<?php

namespace Dinnovos\Amazonas\Controllers\Admin;

use Dinnovos\Amazonas\Main\MainBundleController;

class BlocksController extends MainBundleController
{
    protected $namespace_model = 'Dinnovos\Amazonas\Models\BlockModel';
    protected $namespace_form = 'Dinnovos\Amazonas\Forms\BlockForm';
    protected $namespace_bundle = 'Dinnovos\Amazonas';
    protected $controller = 'Admin/Blocks';
    protected $view = 'Admin/Blocks';
    protected $title = 'Bloques';
}