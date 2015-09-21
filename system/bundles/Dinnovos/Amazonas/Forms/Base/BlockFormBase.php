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
* BlockFormBase
*
* @author Jorge Gaitan
*/

namespace Dinnovos\Amazonas\Forms\Base;

Class BlockFormBase extends \Kodazzi\Form\FormBuilder
{
	protected function config()
	{
		$this->setNameModel('Dinnovos\Amazonas\Models\BlockModel');

		$this->setWidget('label', new \Kodazzi\Form\Fields\String())->setUnique(true);
		$this->setWidget('type', new \Kodazzi\Form\Fields\Options())->setOptions(array('content'=>'Contenido'))->setDefault('content');
		$this->setWidget('status', new \Kodazzi\Form\Fields\Options())->setOptions(array('0'=>'Desactivo', '1'=>'Activo'))->setDefault('0');
		$this->setWidget('sequence', new \Kodazzi\Form\Fields\Integer())->setRequired(false);
	}

    public function getTranslationForm()
    {
        return new \Dinnovos\Amazonas\Forms\BlockTranslationForm();
    }
}