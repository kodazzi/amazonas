<?php

 /**
 * This file is part of the Kodazzi Framework.
 *
 * (c) Jorge Gaitan <jgaitan@kodazzi.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dinnovos\Users\Controllers;

use Dinnovos\Users\Main\BundleController;
use Symfony\Component\HttpFoundation\RedirectResponse;

class RegistrationController extends BundleController
{
    public function step1Action()
    {
        $Form = $this->getForm('Dinnovos\Users\Forms\UserForm');

        $Form->getWidget('first_name')->setTemplate($this->template_row)->setHidden(true);
        $Form->getWidget('last_name')->setTemplate($this->template_row)->setHidden(true);
        $Form->getWidget('email')->setTemplate($this->template_row);
        $Form->getWidget('username')->setTemplate($this->template_row);
        $Form->getWidget('password')->setTemplate($this->template_row);
        $Form->getWidget('token_email')->setTemplate($this->template_row);
        $Form->getWidget('token_email_created')->setTemplate($this->template_row);
        $Form->getWidget('token_forgotten')->setTemplate($this->template_row);
        $Form->getWidget('token_forgotten_created')->setTemplate($this->template_row);
        $Form->getWidget('last_logging')->setTemplate($this->template_row);
        $Form->getWidget('accept_terms')->setTemplate($this->template_row);
        $Form->getWidget('email_confirm')->setTemplate($this->template_row);
        $Form->getWidget('status')->setTemplate($this->template_row);

        $result = $this->saveForm($Form);

        if ( ($result instanceof RedirectResponse) )
        {
            return $result;
        }

        return $this->render('Dinnovos\Users:Registration:step1', array(

        ));
    }

    public function successfulAction()
    {
        return $this->render('Dinnovos\Users:Registration:successful', array(

        ));
    }

    public function failsAction()
    {
        return $this->render('Dinnovos\Users:Registration:fails', array(

        ));
    }

    public function confirmationEmailAction()
    {
        $token = $this->getParameters('token');
        $max_days = $this->max_days_token;
        $status = 2;

        if( preg_match('/^[a-zA-Z0-9]+$/', $token ) )
        {
            $ModelUser = $this->getDB()->model($this->model);

            $User = $ModelUser->fetch(array('token_email' => $token));

            if($User)
            {
                $diff = \Kodazzi\Tools\Date::diff( $User->token_email_created, $this->getTimestamp(), 'd' );

                // Si es mayor o igual a uno o es menor que cero dias dice que ha expirado.
                if( ( $diff >= $max_days ) || ( $diff < 0 ) )
                {
                    // Error 3: Token ha expirado o no es valido
                    $status = 3;
                }
                else if( (int)$User->email_confirm === 1 )
                {
                    // Error 4: El email ya fue confirmado
                    $status = 4;
                }
                else
                {
                    $quantity = $ModelUser->update( array( 'email_confirm' => 1, 'token_email' => null, 'token_email_created' => null ), array('id' => $User->id, 'token_email' => $User->token_email ) );

                    if( $quantity )
                    {
                        $status = 1;

                        $this->sendEmailVerified( $User );
                    }
                }
            }
            else
            {
                // Error 2: Token no valido.
                $status = 2;
            }
        }

        return $this->render('Dinnovos\Users:Registration:confirmation_email',array(
            'status' => $status,
            'max_days' => $max_days
        ));
    }

    protected  function saveForm(\Kodazzi\Form\FormBuilder $Form)
    {
        $View = $this->getView();
        $post = $this->getPOST();
        $name_form = $Form->getNameForm();

        $post[$name_form]['token_email'] = $this->createToken($this->getTimestamp());
        $post[$name_form]['token_email_created'] = $this->getTimestamp();
        $post[$name_form]['email_confirm'] = '0';
        $post[$name_form]['status'] = ($this->need_approval) ? 2 : 1;

        if($Form->bind($post))
        {
            if(array_key_exists('UserForm', $post) && array_key_exists('accept_terms', $post['UserForm']) && $post['UserForm']['accept_terms'] != 1)
            {
                $Form->setError('accept_terms', 'Debe aceptar los t&eacute;rminos y condiciones.');
            }

            if($Form->isValid())
            {
                $result = $Form->save();

                if( $result )
                {
                    $last_id = $Form->getIdentifier();

                    // Enviar email de confirmacion
                    $this->sendWelcomeAndTokenConfirmation($last_id);

                    $View->msgSuccess('Felicitaciones!! el registro ha sido exitoso.');

                    // Si se permite iniciar sesion luego del registro...
                    if($this->start_after_registration)
                    {
                        $User = $this->getDB()->model($this->model)->fetch(array('id'=>$last_id));

                        if($User)
                        {
                            // Crea la tarjeta en la sesion del usuario.
                            $this->createUserCard($User);

                            return $this->redirectResponse( $this->buildUrl($this->route_landing) );
                        }
                    }

                    return $this->redirectResponse( $this->buildUrl('user-successful-registration') );
                }

                return $this->redirectResponse( $this->buildUrl('user-registration-fails') );
            }

            $View->msgError('Ocurrio un error, por favor verifique el formulario');
        }

        $View->set(array(
            'form' => $Form
        ));
    }
}