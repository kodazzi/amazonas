<?php

namespace Dinnovos\Amazonas\Controllers\Admin;

use Dinnovos\Amazonas\Main\AdminBundleController;

class SettingsController extends AdminBundleController
{
    protected $namespace_model = 'Dinnovos\Amazonas\Models\SettingModel';
    protected $namespace_form = 'Dinnovos\Amazonas\Forms\SettingForm';
    protected $namespace_bundle = 'Dinnovos\Amazonas';
    protected $controller = 'Admin/Settings';
    protected $view = 'Admin/Settings';
    protected $title = 'Ajustes';
}