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
* CategoryPageFormBase
*
* @author Jorge Gaitan
*/

namespace Dinnovos\Amazonas\Forms\Base;

Class CategoryPageFormBase extends \Kodazzi\Form\FormBuilder
{
	protected function config()
	{
		$this->setNameModel('Dinnovos\Amazonas\Models\CategoryPageModel');

		$this->setWidget('label', new \Kodazzi\Form\Fields\String())->setRequired(false);
	}

    public function getTranslationForm()
    {
        return new \Dinnovos\Amazonas\Forms\CategoryPageTranslationForm();
    }
}