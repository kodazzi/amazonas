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

use Symfony\Component\HttpFoundation\Response;

class CarouselService
{
    static protected $model = 'Dinnovos\Amazonas\Models\CarouselModel';
    static protected $modelSlides = 'Dinnovos\Amazonas\Models\SlideModel';

    static public function show($where = array())
    {
        if(is_string($where))
        {
            $where = array('mark'=>$where);
        }

        $carousel = self::_carousel($where);

        if($carousel)
        {
            $slides = \Service::get('db')->model(self::$modelSlides)->fetchAll(array('carousel_id'=>$carousel->id), 't.*', \PDO::FETCH_CLASS, array('sequence'=>'ASC'));

            return \Service::get('view')->render('Dinnovos\Amazonas:Carousels:carousel', array(
                'carousel'  => $carousel,
                'slides'    => $slides
            ));
        }

        return '';
    }

    // ------------
    static private function _carousel($where = array(), $fields = '*', $typeFetch = \PDO::FETCH_CLASS, $order = null)
    {
        return \Service::get('db')->model(self::$model)->fetch($where, $fields, $typeFetch, $order);
    }

}