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
        \Kodazzi\Backing\Alias::set('Page', 'Dinnovos\Amazonas\Services\PageService');
        \Kodazzi\Backing\Alias::set('Post', 'Dinnovos\Amazonas\Services\PostService');
        \Kodazzi\Backing\Alias::set('Block', 'Dinnovos\Amazonas\Services\BlockService');
        \Kodazzi\Backing\Alias::set('Carousel', 'Dinnovos\Amazonas\Services\CarouselService');
    }

    private function functionsView()
    {
        \Service::get('view')->addFunction('show_page', function($where = array()){
            return \Page::showContent($where);
        });

        \Service::get('view')->addFunction('show_block', function($where = array()){
            return \Block::showContent($where);
        });

        \Service::get('view')->addFunction('show_carousel', function($where = array()){
            return \Carousel::show($where);
        });
    }
}