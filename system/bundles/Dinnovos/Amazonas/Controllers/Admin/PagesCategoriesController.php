<?php

namespace Dinnovos\Amazonas\Controllers\Admin;

use Dinnovos\Amazonas\Main\AdminBundleController;

class PagesCategoriesController extends AdminBundleController
{
    protected $namespace_model = 'Dinnovos\Amazonas\Models\CategoryPageModel';
    protected $namespace_model_translation = 'Dinnovos\Amazonas\Models\CategoryPageTranslationModel';
    protected $namespace_form = 'Dinnovos\Amazonas\Forms\CategoryPageForm';
    protected $namespace_bundle = 'Dinnovos\Amazonas';
    protected $controller = 'Admin/PagesCategories';
    protected $view = 'Admin/PagesCategories';
    protected $title = 'Categor&iacute;as';
}