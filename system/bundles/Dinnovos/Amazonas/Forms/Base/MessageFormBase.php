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
* MessageFormBase
*
* @author Jorge Gaitan
*/

namespace Dinnovos\Amazonas\Forms\Base;

Class MessageFormBase extends \Kodazzi\Form\FormBuilder
{
	protected function config()
	{
		$this->setNameModel('Dinnovos\Amazonas\Models\MessageModel');

		$this->setWidget('fullname', new \Kodazzi\Form\Fields\String());
		$this->setWidget('email', new \Kodazzi\Form\Fields\Email());
		$this->setWidget('subject', new \Kodazzi\Form\Fields\String());
		$this->setWidget('message', new \Kodazzi\Form\Fields\Note());
		$this->setWidget('status', new \Kodazzi\Form\Fields\Options())->setOptions(array('0'=>'Sin Revisar', '1'=>'Revisado'))->setDefault('0');
	}
}