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
* PostFormBase
*
* @author Jorge Gaitan
*/

namespace Dinnovos\Amazonas\Forms\Base;

Class PostFormBase extends \Kodazzi\Form\FormBuilder
{
	protected function config()
	{
		$this->setNameModel('Dinnovos\Amazonas\Models\PostModel');

		$this->setWidget('status', new \Kodazzi\Form\Fields\Options())->setOptions(array('0'=>'Desactivo', '1'=>'Activo', '2'=>'Borrador'))->setDefault('2');
		$this->setWidget('category_id', new \Kodazzi\Form\Fields\Foreign())->setRequired(false)->setTypeRelation('many-to-one')->definitionRelation('Dinnovos\Amazonas\Models\CategoryPostModel', array('name' => 'category_id', 'foreignField' => 'id') );
		$this->setWidget('sequence', new \Kodazzi\Form\Fields\Integer())->setRequired(false);
	}

    public function getTranslationForm()
    {
        return new \Dinnovos\Amazonas\Forms\PostTranslationForm();
    }
}