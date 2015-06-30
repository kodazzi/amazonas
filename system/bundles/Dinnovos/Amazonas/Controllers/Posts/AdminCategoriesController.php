<?php

namespace Dinnovos\Amazonas\Controllers\Posts;

use Dinnovos\Amazonas\Main\BundleController;

class AdminCategoriesController extends BundleController
{
    protected $namespace_model = 'Dinnovos\Amazonas\Models\CategoryPostModel';
    protected $namespace_form = 'Dinnovos\Amazonas\Forms\CategoryPostForm';
    protected $namespace_bundle = 'Dinnovos\Amazonas';
    protected $controller = 'Posts/AdminCategories';
    protected $view = 'Posts/AdminCategories';
    protected $title = 'Categor&iacute;as';
}