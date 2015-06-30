<?php

namespace Dinnovos\Amazonas\Controllers\Pages;

use Dinnovos\Amazonas\Main\BundleController;

class AdminCategoriesController extends BundleController
{
    protected $namespace_model = 'Dinnovos\Amazonas\Models\CategoryPagesModel';
    protected $namespace_form = 'Dinnovos\Amazonas\Forms\CategoryPagesForm';
    protected $namespace_bundle = 'Dinnovos\Amazonas';
    protected $controller = 'Pages/AdminCategories';
    protected $view = 'Pages/AdminCategories';
    protected $title = 'Categor&iacute;as';
}