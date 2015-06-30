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
 * PostForm
 * 
 * @author Jorge Gaitan
 */
 
namespace Dinnovos\Amazonas\Forms;

Class PostForm extends Base\PostFormBase
{
	protected function change()
	{
        $this->setWidget('slug', new \Kodazzi\Form\Fields\String())->setRequired(false);
	}
}