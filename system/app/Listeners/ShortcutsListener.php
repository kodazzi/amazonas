<?php
/**
 * This file is part of the Kodazzi Framework.
 *
 * (c) Jorge Gaitan <jgaitan@kodazzi.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Listeners;

use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\KernelEvents;

class ShortcutsListener implements EventSubscriberInterface
{
    public function onKernelController(FilterControllerEvent $event)
    {
        $controller = $event->getController();
        $instance = $controller[0];

        if(!($instance instanceof \Dinnovos\Amazonas\Controllers\InstallController))
        {
            $instance->settings = \Settings::prepareSettings();

            $instance->addMethod(function ($label, $default = -1) use ($instance){

                if(isset($instance->settings[$label]))
                {
                    return $instance->settings[$label];
                }

                return ($default === -1) ? null : $default;

            }, "getSetting");
        }

        $instance->addMethod(function () use ($instance){
            return $instance->forward('Dinnovos\Amazonas:Errors:error402');
        }, "responseError402");

        $instance->addMethod(function () use ($instance){
            return $instance->forward('Dinnovos\Amazonas:Errors:error404');
        }, "responseError404");

        $instance->addMethod(function () use ($instance){
            return $instance->forward('Dinnovos\Amazonas:Errors:error405');
        }, "responseError402");

        $instance->addMethod(function () use ($instance){
            return $instance->forward('Dinnovos\Amazonas:Errors:error423');
        }, "responseError423");
    }

    public static function getSubscribedEvents()
    {
        return array(
            KernelEvents::CONTROLLER => array('onKernelController', 10)
        );
    }
}