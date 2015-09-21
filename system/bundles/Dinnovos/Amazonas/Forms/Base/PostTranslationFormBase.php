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
* PostTranslationFormBase
*
* @author Jorge Gaitan
*/

namespace Dinnovos\Amazonas\Forms\Base;

Class PostTranslationFormBase extends \Kodazzi\Form\FormBuilder
{
	protected function config()
	{
		$this->setNameModel('Dinnovos\Amazonas\Models\PostTranslationModel');

		$this->setWidget('title', new \Kodazzi\Form\Fields\String());
		$this->setWidget('content', new \Kodazzi\Form\Fields\Editor());
		$this->setWidget('meta_description', new \Kodazzi\Form\Fields\Note())->setRequired(false);
		$this->setWidget('translatable_id', new \Kodazzi\Form\Fields\Foreign())->setTypeRelation('many-to-one')->definitionRelation('Dinnovos\Amazonas\Models\PostModel', array('name' => 'translatable_id', 'foreignField' => 'id') );
		$this->setWidget('language_id', new \Kodazzi\Form\Fields\Foreign())->setTypeRelation('many-to-one')->definitionRelation('Dinnovos\Amazonas\Models\LanguageModel', array('name' => 'language_id', 'foreignField' => 'id') );
	}
}