<?php
/**
 * This file is part of the Yulois Framework.
 *
 * (c) Jorge Gaitan <info.yulois@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Yulois\Kernel;

Class AppKernel extends Kernel
{
    public static function registryBundles()
    {
        return array(
            'Dinnovos\Amazonas\\',
            'Dinnovos\Installation\\'
        );
    }
}