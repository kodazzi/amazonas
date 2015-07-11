<?php

namespace Dinnovos\Amazonas\Controllers\Admin;

use Dinnovos\Amazonas\Main\MainBundleController;

class PostsCategoriesController extends MainBundleController
{
    protected $namespace_model = 'Dinnovos\Amazonas\Models\CategoryPostModel';
    protected $namespace_form = 'Dinnovos\Amazonas\Forms\CategoryPostForm';
    protected $namespace_bundle = 'Dinnovos\Amazonas';
    protected $controller = 'Admin/PostsCategories';
    protected $view = 'Admin/PostsCategories';
    protected $title = 'Categor&iacute;as';
}