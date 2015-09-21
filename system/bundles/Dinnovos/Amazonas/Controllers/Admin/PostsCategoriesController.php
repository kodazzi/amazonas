<?php

namespace Dinnovos\Amazonas\Controllers\Admin;

use Dinnovos\Amazonas\Main\AdminBundleController;

class PostsCategoriesController extends AdminBundleController
{
    protected $namespace_model = 'Dinnovos\Amazonas\Models\CategoryPostModel';
    protected $namespace_model_translation = 'Dinnovos\Amazonas\Models\CategoryPostTranslationModel';
    protected $namespace_form = 'Dinnovos\Amazonas\Forms\CategoryPostForm';
    protected $namespace_bundle = 'Dinnovos\Amazonas';
    protected $controller = 'Admin/PostsCategories';
    protected $view = 'Admin/PostsCategories';
    protected $title = 'Categor&iacute;as';
}