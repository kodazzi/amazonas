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
 * CategoryPostForm
 * 
 * @author Jorge Gaitan
 */
 
namespace Dinnovos\Amazonas\Forms;

Class CategoryPostForm extends Base\CategoryPostFormBase
{
	protected function change()
	{
        $this->setWidget('slug', new \Kodazzi\Form\Fields\String())->setRequired(false);
	}
}