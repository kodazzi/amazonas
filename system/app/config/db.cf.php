<?php
/**
 * This file is part of the Yulois Framework.
 *
 * (c) Jorge Gaitan <info.yulois@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

return array(
    'prod' => array(
        'default' => array(
            'host'      => 'localhost',
            'dbname'    => 'prod_db',
            'user'      => 'prod_user',
            'password'  => 'prod_password',
            'driver'    => 'pdo_mysql',
            'charset'   => 'utf8'
        ),
    ),

    'dev' => array(
        'default' => array(
            'host'      => 'localhost',
            'dbname'    => 'ds_amazonas',
            'user'      => 'root',
            'password'  => '',
            'driver'    => 'pdo_mysql',
            'charset'   => 'utf8'
        ),
    ),

    'prefix' => 'as_'
);