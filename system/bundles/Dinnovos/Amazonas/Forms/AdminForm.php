<?php

/*
 * This file is part of the Kodazzi Framework.
 *
 * (c) Jorge Gaitan <jgatan@kodazzi.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * AdminForm
 * 
 * @author Jorge Gaitan
 */
 
namespace Dinnovos\Amazonas\Forms;

Class AdminForm extends Base\AdminFormBase
{
	protected function change()
	{
        $Widgets = $this->getWidgets();

        $Widgets['token_forgotten']->setHidden(true);
        $Widgets['token_forgotten_created']->setHidden(true);
        $Widgets['last_logging']->setHidden(true);


	}
}