<?php
 /**
 * This file is part of the Kodazzi Framework.
 *
 * (c) Jorge Gaitan <info.yulois@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

return array(

    'access_control' => array(
        array(
            'pattern'           => '^/panel',
            'role'              => 'ADMIN',
            'login_route'       => 'login',
            'forbidden_route'   => 'forbidden'
        ),
        array(
            'pattern'           => '^/usuario',
            'role'              => 'USER',
            'login_route'       => 'user-login',
            'forbidden_route'   => 'user-forbidden'
        )
    )
);