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
* PageFormBase
*
* @author Jorge Gaitan
*/

namespace Dinnovos\Amazonas\Forms\Base;

Class PageFormBase extends \Kodazzi\Form\FormBuilder
{
	protected function config()
	{
		$this->setNameModel('Dinnovos\Amazonas\Models\PageModel');

 		$this->setWidget('title', new \Kodazzi\Form\Fields\String());
 		$this->setWidget('content', new \Kodazzi\Form\Fields\Editor());
 		$this->setWidget('status', new \Kodazzi\Form\Fields\Options())->setOptions(array('0'=>'Desactivo', '1'=>'Activo', '2'=>'Borrador'))->setDefault('2');
 		$this->setWidget('category_id', new \Kodazzi\Form\Fields\Foreign())->setRequired(false)->setTypeRelation('many-to-one')->definitionRelation('Dinnovos\Amazonas\Models\CategoryPagesModel', array('name' => 'category_id', 'foreignField' => 'id') );
 		$this->setWidget('meta_description', new \Kodazzi\Form\Fields\Note())->setRequired(false);
 		$this->setWidget('sequence', new \Kodazzi\Form\Fields\Integer())->setRequired(false);
 		$this->setWidget('mark', new \Kodazzi\Form\Fields\String())->setRequired(false);
 		$this->setWidget('parent_id', new \Kodazzi\Form\Fields\Foreign())->setTypeRelation('many-to-one-self-referencing')->definitionRelation('Dinnovos\Amazonas\Models\PageModel', array('name' => 'parent_id', 'foreignField' => 'id') )->setRequired(false);
	}
}