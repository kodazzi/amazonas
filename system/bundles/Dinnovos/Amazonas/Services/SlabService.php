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

class SlabService
{
    static protected $model = 'Dinnovos\Amazonas\Models\BlockModel';

    static public function get($where = array(), $fields = '*', $typeFetch = \PDO::FETCH_CLASS, $order = null)
    {
        if(is_string($where))
        {
            $where = array('label'=>$where);
        }

        return;
    }

    static public function showContent($where = array())
    {
        if(is_string($where))
        {
            $where = array('a.label' => $where);
        }

        $block = self::_block($where);

        if($block && isset($block->content))
        {
            return $block->content;
        }

        return '';
    }

    // ------------
    static private function _block($where = array(), $fields = '*', $typeFetch = \PDO::FETCH_CLASS, $order = null)
    {
        return \Service::get('db')->model(self::$model)->getTranslation('es')->fetch($where, $fields, $typeFetch, $order);
    }
}