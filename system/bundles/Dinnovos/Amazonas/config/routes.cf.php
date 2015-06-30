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

/***************************************** Installation ******************************************/
$routes->add(
    'Install',
    new Route('/', array( 'controller' => 'Dinnovos\Amazonas:Install:step1' ))
);

$routes->add(
    'installation-step2',
    new Route('/installation-step2', array( 'controller' => 'Dinnovos\Amazonas:Install:step2' ))
);

/******************************************* Panel ************************************************/
$routes->add(
    'panel-login',
    new Route('/admin/login', array('controller' => 'Dinnovos\Amazonas:Session:login'))
);

$routes->add(
    'panel-dashboard',
    new Route('/admin/dashboard', array('controller' => 'Dinnovos\Amazonas:Dashboard:index'))
);

// --- Paginas ----
$routes->add(
    'page-show',
    new Route("/pagina/{slug}", array('controller' => 'Dinnovos\Amazonas:Page:show'))
);

$routes->add(
    '@default-admin',
    new Route(
        '/admin/{controller}/{action}/{param1}/{param2}/{param3}/{param4}/{param5}',
        array('_bundle' => 'Dinnovos\Amazonas', 'param1' => null, 'param2' => null, 'param3' => null, 'param4' => null, 'param5' => null)
    )
);

// --- Config ----
$routes->add(
    '@default-config',
    new Route(
        '/config/{controller}/{action}/{param1}/{param2}/{param3}/{param4}/{param5}',
        array('_bundle' => 'Dinnovos\Amazonas', 'param1' => null, 'param2' => null, 'param3' => null, 'param4' => null, 'param5' => null)
    )
);