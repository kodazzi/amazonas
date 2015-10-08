<?php
/*
 * This file is part of the Kodazzi Framework.
 *
 * (c) Jorge Gaitan <jgaitan@kodazzi.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Symfony\Component\Routing\Route;


$routes->add(
    'user-registration',
    new Route('/registro-usuario', array('controller' => 'Dinnovos\Users:Registration:step1'))
);

$routes->add(
    'user-successful-registration',
    new Route('/registro-exitoso', array('controller' => 'Dinnovos\Users:Registration:successful'))
);

$routes->add(
    'user-registration-fails',
    new Route('/registro-fallido', array('controller' => 'Dinnovos\Users:Registration:fails'))
);

$routes->add(
    'user-token-confirmation-email',
    new Route('/registro/confirmacion/{token}', array('controller' => 'Dinnovos\Users:Registration:confirmationEmail'), array('token' => '^[a-zA-Z0-9]+$'))
);

//----------------------------------------------------------------------------------------------------------------------

$routes->add(
    'user-login',
    new Route('/iniciar-sesion', array('controller' => 'Dinnovos\Users:Session:login'))
);

$routes->add(
    'user-logout',
    new Route('/salir-sesion', array('controller' => 'Dinnovos\Users:Session:logout'))
);

$routes->add(
    'user-forgotten-password',
    new Route('/olvido-clave', array('controller' => 'Dinnovos\Users:Session:forgottenPassword'))
);

$routes->add(
    'user-not-approved',
    new Route('/usuario/cuenta-no-aprobada', array('controller' => 'Dinnovos\Users:Session:notApproved'))
);

$routes->add(
    'user-invalid-account',
    new Route('/usuario/cuenta-no-valida', array('controller' => 'Dinnovos\Users:Session:invalidAccount'))
);

$routes->add(
    'user-dashboard',
    new Route('/usuario/escritorio', array('controller' => 'Dinnovos\Users:dashboard:index'))
);

// Para los usuarios que no ha confirmado la cuenta de correo.
$routes->add(
    'user-unconfirme-email',
    new Route('/invalido/email', array('controller' => 'Dinnovos\Users:Session:unconfirmeEmail'))
);

// Envia email para confirmar correo
$routes->add(
    'user-send-confirmation-email',
    new Route('/invalido/enviar-confirmacion-email', array('controller' => 'Dinnovos\Users:Session:sendConfirmationEmail'))
);

// Permite acceder al formulario para cambiar la clave.
$routes->add(
    'user-modify-password',
    new Route('/usuario/modificar-clave/{token}', array('controller' => 'Dinnovos\Users:Session:modifyPassword'), array('token' => '^[a-zA-Z0-9]+$'))
);

$routes->add(
    'user-dashboard',
    new Route('/usuario/escritorio', array('controller' => 'Dinnovos\Users:dashboard:index'))
);

//----------------------------------------------------------------------------------------------------------------------

$routes->add(
    '@default-admin-user',
    new Route(
        '/panel-users/{controller}/{action}/{param1}/{param2}/{param3}/{param4}/{param5}',
        array('_bundle' => 'Dinnovos\Users', 'param1' => null, 'param2' => null, 'param3' => null, 'param4' => null, 'param5' => null)
    )
);