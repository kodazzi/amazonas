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
* CategoryPagesFormBase
*
* @author Jorge Gaitan
*/

namespace Dinnovos\Amazonas\Forms\Base;

Class CategoryPagesFormBase extends \Kodazzi\Form\FormBuilder
{
	protected function config()
	{
		$this->setNameModel('Dinnovos\Amazonas\Models\CategoryPagesModel');

 		$this->setWidget('title', new \Kodazzi\Form\Fields\Title());
 		$this->setWidget('mark', new \Kodazzi\Form\Fields\String())->setRequired(false);
	}
}