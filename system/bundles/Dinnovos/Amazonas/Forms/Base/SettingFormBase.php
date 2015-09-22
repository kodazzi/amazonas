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
* SettingFormBase
*
* @author Jorge Gaitan
*/

namespace Dinnovos\Amazonas\Forms\Base;

Class SettingFormBase extends \Kodazzi\Form\FormBuilder
{
	protected function config()
	{
		$this->setNameModel('Dinnovos\Amazonas\Models\SettingModel');

		$this->setWidget('title', new \Kodazzi\Form\Fields\String());
		$this->setWidget('content', new \Kodazzi\Form\Fields\String());
		$this->setWidget('label', new \Kodazzi\Form\Fields\String());
		$this->setWidget('help', new \Kodazzi\Form\Fields\String())->setRequired(false);
		$this->setWidget('type', new \Kodazzi\Form\Fields\Options())->setOptions(array('string'=>'Cadena', 'integer'=>'Valor Entero', 'boolean'=>'Verdadero o Falso'))->setDefault('string');
		$this->setWidget('allow_show', new \Kodazzi\Form\Fields\Options())->setOptions(array('0'=>'No', '1'=>'SI'))->setDefault('1');
		$this->setWidget('allow_edit', new \Kodazzi\Form\Fields\Options())->setOptions(array('0'=>'No', '1'=>'SI'))->setDefault('1');
		$this->setWidget('allow_delete', new \Kodazzi\Form\Fields\Options())->setOptions(array('0'=>'No', '1'=>'SI'))->setDefault('1');
	}
}