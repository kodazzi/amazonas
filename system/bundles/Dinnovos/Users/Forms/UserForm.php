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
 * UserForm
 * 
 * @author Jorge Gaitan
 */
 
namespace Dinnovos\Users\Forms;

Class UserForm extends Base\UserFormBase
{
	protected function change()
	{
        $this->getWidget('first_name')->setValueLabel('Nombre');
        $this->getWidget('last_name')->setValueLabel('Apellido');
        $this->getWidget('email')->setValueLabel('Correo Electr&oacute;nico');
        $this->getWidget('username')->setValueLabel('Usuario');
        $this->getWidget('password')->setValueLabel('Clave');
        $this->getWidget('accept_terms')->setValueLabel('Terminos y Condiciones')->setHelp('Declaro que estoy conforme con todos los <a href="" target="_blank">t&eacute;rminos y condiciones</a> del portal.');

        $this->getWidget('email_confirm')->setHidden(true);
        $this->getWidget('status')->setHidden(true);
        $this->getWidget('token_email')->setHidden(true);
        $this->getWidget('token_email_created')->setHidden(true);
        $this->getWidget('token_forgotten')->setHidden(true);
        $this->getWidget('token_forgotten_created')->setHidden(true);
        $this->getWidget('last_logging')->setHidden(true);
	}
}