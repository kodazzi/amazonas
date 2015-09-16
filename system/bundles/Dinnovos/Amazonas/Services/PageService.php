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

class PageService
{
    static protected $model = 'Dinnovos\Amazonas\Models\PageModel';

    static public function get($where = array(), $fields = '*', $typeFetch = \PDO::FETCH_CLASS, $order = null)
    {
        if(is_string($where))
        {
            $where = array('label'=>$where);
        }

        return self::_page($where, $fields, $typeFetch, $order);
    }

    static public function getList($where = array(), $fields = '*', $typeFetch = \PDO::FETCH_CLASS, $order = null)
    {
        if(is_string($where))
        {
            $where = array('label'=>$where);
        }

        return self::_pages($where, $fields, $typeFetch, $order);
    }

    static public function showContent($where = array())
    {
        if(is_string($where))
        {
            $where = array('label'=>$where);
        }

        $block = self::_page($where);

        if($block && isset($block->content))
        {
            return $block->content;
        }

        return '';
    }

    // ------------
    static private function _page($where = array(), $fields = '*', $typeFetch = \PDO::FETCH_CLASS, $order = null)
    {
        return \Service::get('db')->model(self::$model)->fetch($where, $fields, $typeFetch, $order);
    }

    static private function _pages($where = array(), $fields = '*', $typeFetch = \PDO::FETCH_CLASS, $order = null)
    {
        return \Service::get('db')->model(self::$model)->fetchAll($where, $fields, $typeFetch, $order);
    }
}