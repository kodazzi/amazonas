<?php

namespace Dinnovos\Amazonas\Controllers\Admin;

use Dinnovos\Amazonas\Main\MainBundleController;

class PagesCategoriesController extends MainBundleController
{
    protected $namespace_model = 'Dinnovos\Amazonas\Models\CategoryPagesModel';
    protected $namespace_form = 'Dinnovos\Amazonas\Forms\CategoryPagesForm';
    protected $namespace_bundle = 'Dinnovos\Amazonas';
    protected $controller = 'Admin/PagesCategories';
    protected $view = 'Admin/PagesCategories';
    protected $title = 'Categor&iacute;as';
}