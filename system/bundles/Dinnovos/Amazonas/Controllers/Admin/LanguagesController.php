<?php

namespace Dinnovos\Amazonas\Controllers\Admin;

use Dinnovos\Amazonas\Main\AdminBundleController;

class LanguagesController extends AdminBundleController
{
    protected $namespace_model = 'Dinnovos\Amazonas\Models\LanguageModel';
    protected $namespace_form = 'Dinnovos\Amazonas\Forms\LanguageForm';
    protected $namespace_bundle = 'Dinnovos\Amazonas';
    protected $controller = 'Admin/Languages';
    protected $view = 'Admin/Languages';
    protected $title = 'Lenguajes';
}