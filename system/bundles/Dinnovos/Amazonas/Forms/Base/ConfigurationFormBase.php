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
* ConfigurationFormBase
*
* @author Jorge Gaitan
*/

namespace Dinnovos\Amazonas\Forms\Base;

Class ConfigurationFormBase extends \Kodazzi\Form\FormBuilder
{
	protected function config()
	{
		$this->setNameModel('Dinnovos\Amazonas\Models\ConfigurationModel');

 		$this->setWidget('title', new \Kodazzi\Form\Fields\String());
 		$this->setWidget('tag', new \Kodazzi\Form\Fields\String());
 		$this->setWidget('content', new \Kodazzi\Form\Fields\String());
 		$this->setWidget('help', new \Kodazzi\Form\Fields\String());
	}
}