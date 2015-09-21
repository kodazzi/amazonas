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
* CarouselTranslationFormBase
*
* @author Jorge Gaitan
*/

namespace Dinnovos\Amazonas\Forms\Base;

Class CarouselTranslationFormBase extends \Kodazzi\Form\FormBuilder
{
	protected function config()
	{
		$this->setNameModel('Dinnovos\Amazonas\Models\CarouselTranslationModel');

		$this->setWidget('title', new \Kodazzi\Form\Fields\String());
		$this->setWidget('translatable_id', new \Kodazzi\Form\Fields\Foreign())->setTypeRelation('many-to-one')->definitionRelation('Dinnovos\Amazonas\Models\CarouselModel', array('name' => 'translatable_id', 'foreignField' => 'id') );
		$this->setWidget('language_id', new \Kodazzi\Form\Fields\Foreign())->setTypeRelation('many-to-one')->definitionRelation('Dinnovos\Amazonas\Models\LanguageModel', array('name' => 'language_id', 'foreignField' => 'id') );
	}
}