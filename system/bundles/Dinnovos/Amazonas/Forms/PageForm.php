<?php

/*
 * This file is part of the Kodazzi Framework.
 *
 * (c) Jorge Gaitan <jorge@Kodazzi.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * PageForm
 * 
 * @author Jorge Gaitan
 */
 
namespace Dinnovos\Amazonas\Forms;

Class PageForm extends Base\PageFormBase
{
	protected function change()
	{
        $this->setWidget('slug', new \Kodazzi\Form\Fields\String())->setRequired(false);
	}
}