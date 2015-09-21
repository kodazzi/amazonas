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
* SlideTranslationFormBase
*
* @author Jorge Gaitan
*/

namespace Dinnovos\Amazonas\Forms\Base;

Class SlideTranslationFormBase extends \Kodazzi\Form\FormBuilder
{
	protected function config()
	{
		$this->setNameModel('Dinnovos\Amazonas\Models\SlideTranslationModel');

		$this->setWidget('title', new \Kodazzi\Form\Fields\String());
		$this->setWidget('description', new \Kodazzi\Form\Fields\Note())->setRequired(false);
		$this->setWidget('image', new \Kodazzi\Form\Fields\String())->setRequired(false);
		$this->setWidget('translatable_id', new \Kodazzi\Form\Fields\Foreign())->setTypeRelation('many-to-one')->definitionRelation('Dinnovos\Amazonas\Models\SlideModel', array('name' => 'translatable_id', 'foreignField' => 'id') );
		$this->setWidget('language_id', new \Kodazzi\Form\Fields\Foreign())->setTypeRelation('many-to-one')->definitionRelation('Dinnovos\Amazonas\Models\LanguageModel', array('name' => 'language_id', 'foreignField' => 'id') );
	}
}