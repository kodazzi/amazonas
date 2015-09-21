<?php
 /**
 * This file is part of the Yulois Framework.
 *
 * (c) Jorge Gaitan <info.yulois@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dinnovos\Amazonas\Services;

class SettingsService
{
    static protected $model = 'Dinnovos\Amazonas\Models\SettingModel';

    static public function prepareSettings()
    {
        $prepare = array();

        $settings = \Service::get('db')->model(self::$model)->fetchAll();

        foreach($settings as $setting)
        {
            $value = null;

            if($setting->type == 'integer')
            {
                $value = (int)$setting->content;
            }
            else if($setting->type == 'boolean')
            {
                $value = ($setting->content == 1 || $setting->content == 'true') ? true : false;
            }
            else
            {
                $value = $setting->content;
            }

            $prepare[$setting->label] = $value;
        }

        return $prepare;
    }
}