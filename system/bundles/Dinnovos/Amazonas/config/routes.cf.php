<?php
/*
 * This file is part of the System Bundles EventoSalud.
 *
 * (c) Jorge Gaitan <webmaster@dinnovos.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Symfony\Component\Routing\Route;

/***************************************** Frontend ******************************************/

$routes->add(
    'page-show',
    new Route("/pagina/{slug}", array('controller' => 'Dinnovos\Amazonas:Pages:show'))
);

$routes->add(
    'contact',
    new Route("/contactenos", array('controller' => 'Dinnovos\Amazonas:Contact:show'))
);

$routes->add(
    'captcha',
    new Route('/code/captcha', array( 'controller' => 'Dinnovos\Amazonas:Captcha:show') )
);

/****************************************** Install ************************************************/

$routes->add(
    'installation-step2',
    new Route('/installation-step2', array( 'controller' => 'Dinnovos\Amazonas:Install:step2' ))
);

/******************************************* Panel ************************************************/

$routes->add(
    'login',
    new Route('/login', array('controller' => 'Dinnovos\Amazonas:Session:login'))
);

$routes->add(
    'logout',
    new Route('/logout', array('controller' => 'Dinnovos\Amazonas:Session:logout'))
);

$routes->add(
    'forbidden',
    new Route('/forbidden', array('controller' => 'Dinnovos\Amazonas:Session:forbidden'))
);

$routes->add(
    'panel-dashboard',
    new Route('/panel/dashboard', array('controller' => 'Dinnovos\Amazonas:Admin/Dashboard:index'))
);

// Permite acceder al formulario para cambiar la clave.
$routes->add(
    'admin-modify-password',
    new Route('/amazonas/modificar-clave/{token}', array('controller' => 'Dinnovos\Amazonas:Session:modifyPassword'), array('token' => '^[a-zA-Z0-9]+$'))
);

$routes->add(
    'admin-forgotten-password',
    new Route('/amazonas/olvido-clave', array('controller' => 'Dinnovos\Amazonas:Session:forgottenPassword'))
);

/******************************************* Upload ************************************************/

$routes->add(
    'upload-files',
    new Route('/upload/files', array('controller' => 'Dinnovos\Amazonas:Upload:upload'))
);

$routes->add(
    'upload-display-files',
    new Route('/upload/display-files', array('controller' => 'Dinnovos\Amazonas:Upload:display'))
);

$routes->add(
    'upload-rename-file',
    new Route('/upload/rename-file', array('controller' => 'Dinnovos\Amazonas:Upload:rename'))
);

$routes->add(
    'upload-delete-file',
    new Route('/upload/delete-file', array('controller' => 'Dinnovos\Amazonas:Upload:delete'))
);


$routes->add(
    '@default-admin',
    new Route(
        '/panel/{controller}/{action}/{param1}/{param2}/{param3}/{param4}/{param5}',
        array('_bundle' => 'Dinnovos\Amazonas', 'param1' => null, 'param2' => null, 'param3' => null, 'param4' => null, 'param5' => null)
    )
);