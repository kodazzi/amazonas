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
* AdminFormBase
*
* @author Jorge Gaitan
*/

namespace Dinnovos\Amazonas\Forms\Base;

Class AdminFormBase extends \Kodazzi\Form\FormBuilder
{
	protected function config()
	{
		$this->setNameModel('Dinnovos\Amazonas\Models\AdminModel');

		$this->setWidget('first_name', new \Kodazzi\Form\Fields\String());
		$this->setWidget('last_name', new \Kodazzi\Form\Fields\String());
		$this->setWidget('email', new \Kodazzi\Form\Fields\Email())->setUnique(true);
		$this->setWidget('username', new \Kodazzi\Form\Fields\Login())->setUnique(true);
		$this->setWidget('password', new \Kodazzi\Form\Fields\Password());
		$this->setWidget('super_admin', new \Kodazzi\Form\Fields\Options())->setOptions(array('0'=>'No', '1'=>'Si'))->setDefault('0');
		$this->setWidget('token_forgotten', new \Kodazzi\Form\Fields\String())->setRequired(false);
		$this->setWidget('token_forgotten_created', new \Kodazzi\Form\Fields\Datetime())->setRequired(false);
		$this->setWidget('last_logging', new \Kodazzi\Form\Fields\Datetime())->setRequired(false);
		$this->setWidget('status', new \Kodazzi\Form\Fields\Options())->setOptions(array('0'=>'Desactivo', '1'=>'Activo', '2'=>'Espera'))->setDefault('2');
	}
}