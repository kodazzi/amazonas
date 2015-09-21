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
* LanguageFormBase
*
* @author Jorge Gaitan
*/

namespace Dinnovos\Amazonas\Forms\Base;

Class LanguageFormBase extends \Kodazzi\Form\FormBuilder
{
	protected function config()
	{
		$this->setNameModel('Dinnovos\Amazonas\Models\LanguageModel');

		$this->setWidget('name', new \Kodazzi\Form\Fields\String());
		$this->setWidget('code', new \Kodazzi\Form\Fields\String());
		$this->setWidget('by_default', new \Kodazzi\Form\Fields\Integer());
	}
}