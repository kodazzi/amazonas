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
 * SlideTranslationForm
 * 
 * @author Jorge Gaitan
 */
 
namespace Dinnovos\Amazonas\Forms;

Class SlideTranslationForm extends Base\SlideTranslationFormBase
{
	protected function change()
	{
        $this->getWidget('image')->setPlaceholder('Haga clic para seleccionar una imagen.');
	}
}