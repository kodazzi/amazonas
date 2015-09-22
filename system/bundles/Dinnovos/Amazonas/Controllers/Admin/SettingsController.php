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

    public function deleteAction()
    {
        $id = $this->getParameters('param1');
        $Item = \Service::get('db')->model($this->namespace_model)->fetch(array('id'=>$id));

        if($Item)
        {
            if($Item->allow_delete == 1)
            {
                if($this->namespace_model_translation)
                {
                    \Service::get('db')->model($this->namespace_model_translation)->delete(array('translatable_id'=>$id));
                }

                \Service::get('db')->model($this->namespace_model)->delete(array('id'=>$id));
            }

            return $this->redirectResponse( $this->buildUrl($this->default_route, array('bundle'=>'pages', 'controller'=> $this->controller, 'action'=>'list')) );
        }
    }
}