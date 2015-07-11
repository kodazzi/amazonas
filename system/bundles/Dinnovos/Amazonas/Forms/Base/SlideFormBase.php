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
* SlideFormBase
*
* @author Jorge Gaitan
*/

namespace Dinnovos\Amazonas\Forms\Base;

Class SlideFormBase extends \Kodazzi\Form\FormBuilder
{
	protected function config()
	{
		$this->setNameModel('Dinnovos\Amazonas\Models\SlideModel');

 		$this->setWidget('title', new \Kodazzi\Form\Fields\String());
 		$this->setWidget('image', new \Kodazzi\Form\Fields\String())->setRequired(false);
 		$this->setWidget('description', new \Kodazzi\Form\Fields\Note())->setRequired(false);
 		$this->setWidget('carousel_id', new \Kodazzi\Form\Fields\Foreign())->setTypeRelation('many-to-one')->definitionRelation('Dinnovos\Amazonas\Models\CarouselModel', array('name' => 'carousel_id', 'foreignField' => 'id') );
 		$this->setWidget('sequence', new \Kodazzi\Form\Fields\Integer())->setRequired(false);
	}
}