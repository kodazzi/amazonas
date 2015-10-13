<?php
/**
 * This file is part of the Kodazzi Framework.
 *
 * (c) Jorge Gaitan <jgaitan@kodazzi.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dinnovos\Amazonas\Providers;

use Kodazzi\Container\ServiceProviderInterface;
use Kodazzi\Backing\Alias;

class AmazonasProviders implements ServiceProviderInterface
{
    public function register()
    {
        $this->services();
        $this->alias();
        $this->functionsView();
    }

    private function services()
    {

    }

    private function alias()
    {
        Alias::set('Page', 'Dinnovos\Amazonas\Services\PageService');
        Alias::set('Post', 'Dinnovos\Amazonas\Services\PostService');
        Alias::set('Slab', 'Dinnovos\Amazonas\Services\SlabService');
        Alias::set('Carousel', 'Dinnovos\Amazonas\Services\CarouselService');
        Alias::set('Settings', 'Dinnovos\Amazonas\Services\SettingsService');
    }

    private function functionsView()
    {
        \Service::get('view')->addFunction('page', function($where = array()){
            return \Page::showContent($where);
        });

        \Service::get('view')->addFunction('slab', function($where = array()){
            return \Slab::showContent($where);
        });

        \Service::get('view')->addFunction('carousel', function($where = array()){
            return \Carousel::show($where);
        });
    }
}