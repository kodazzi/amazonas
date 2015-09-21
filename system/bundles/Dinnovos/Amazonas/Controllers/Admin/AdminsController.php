<?php

namespace Dinnovos\Amazonas\Controllers\Admin;

use Dinnovos\Amazonas\Main\AdminBundleController;

class AdminsController extends AdminBundleController
{
    protected $namespace_model = 'Dinnovos\Amazonas\Models\AdminModel';
    protected $namespace_form = 'Dinnovos\Amazonas\Forms\AdminForm';
    protected $namespace_bundle = 'Dinnovos\Amazonas';
    protected $controller = 'Admin/Admins';
    protected $view = 'Admin/Admins';
    protected $title = 'Administradores';
}