<?php

namespace Dinnovos\Users\Controllers;

use Dinnovos\Users\Main\BundleController;

class SessionController extends BundleController
{
    public function loginAction()
    {
        $post = $this->getPOST();
        $I18n = \Service::get('translator');
        $errors = array();

        if($this->getUserCardManager()->getCard() && $this->getUserCardManager()->getCard()->getRole() == 'USER')
        {
            return $this->redirectResponse($this->buildUrl($this->route_landing));
        }

        if(count($post))
        {
            $username = (array_key_exists('_username', $post) && $post['_username'] != '') ? $post['_username'] : null;
            $password = (array_key_exists('_password', $post) && $post['_password'] != '') ? $post['_password'] : null;

            if( !$username )
            {
                $errors['_username'] = $I18n->get('user.user_required');
            }

            if( !$password )
            {
                $errors['_password'] = $I18n->get('user.password_required');
            }

            if(count($errors) == 0)
            {
                // Busca al usuario en la base de datos.
                $User = $this->searchUser($username, $password);

                if($User)
                {
                    // Espera
                    if($User->status == 2)
                    {
                        if($this->need_approval)
                        {
                            return $this->redirectResponse( $this->buildUrl('user-not-approved') );
                        }

                        if(!$this->isValidEmail($User))
                        {
                            return $this->redirectResponse( $this->buildUrl('user-unconfirme-email') );
                        }
                    }

                    // Activo
                    if($User->status == 1)
                    {
                        if(!$this->isValidEmail($User))
                        {
                            // Agrega el id del usuario en la sesion para generar el token y enviarlo por correo.
                            $this->getSession()->set('tmp_user_id', $User->id);

                            return $this->redirectResponse( $this->buildUrl('user-unconfirme-email') );
                        }
                    }

                    // Desactivo
                    if($User->status == 0)
                    {
                        return $this->redirectResponse( $this->buildUrl('user-invalid-account') );
                    }

                    $User->last_logging = $this->getTimestamp();

                    // Actualiza la fecha del ultimo login del usuario
                    $this->getDB()->model($this->model)->save($User);

                    $this->createUserCard($User);

                    $this->getView()->msgSuccess($I18n->get('user.welcome')." {$User->first_name} {$User->last_name}");

                    return $this->redirectResponse($this->buildUrl($this->route_landing));
                }
                else
                {
                    $this->getView()->msgSuccess($I18n->get('user.login_failed'));
                    $errors['global'] = "El usuario o la clave es incorrecta.";
                }
            }
        }

        return $this->render('Dinnovos\Users:Session:login', array(
            'errors' => $errors
        ));
	}

    public function logoutAction()
    {
        $this->getUserCardManager()->clear();

        return $this->redirectResponse($this->buildUrl('user-login'));
    }

    public function forbiddenAction()
    {
        return $this->render('Dinnovos\Users:Session:forbidden');
    }

    public function notApprovedAction()
    {
        return $this->render('Dinnovos\Users:Session:not_approved',array());
    }

    public function invalidAccountAction()
    {
        return $this->render('Dinnovos\Users:Session:invalid_account',array());
    }

    public function unconfirmeEmailAction()
    {
        return $this->render('Dinnovos\Users:Session:unconfirme_email',array());
    }

    public function sendConfirmationEmailAction()
    {
        $user_id = $this->getSession()->get('tmp_user_id');

        // Si no existe el id del usuario en la sesion lo redirecciona al login
        if(!$user_id)
        {
            return $this->redirectResponse( $this->buildUrl('user-login') );
        }

        $ModelUser = $this->getDB()->model($this->model);
        $User = $ModelUser->fetch( array('id' => (int)$user_id ) );

        if(!$User)
        {
            return $this->redirectResponse( $this->buildUrl('user-invalid-account') );
        }

        // Si el email ya esta confirmado se redirecciona al user-login
        if( $User->email_confirm == 1 )
        {
            return $this->redirectResponse( $this->buildUrl('user-login') );
        }

        // Actualiza la tienda en el registro con el nuevo token
        $ModelUser->update( array('token_email' => $this->createToken($this->getTimestamp()), 'token_email_created' => $this->getTimestamp()), array('id' => $User->id ) );

        // Envia el email de confirmacion.
        if($this->sendTokenConfirmationEmail($User->id))
        {
            $status = 1;
        }
        else
        {
            // Si el email no se envia indica al usuario que ocurrio un error e intente mas tarde
            $status = 2;
        }

        //Elimina de la sesion el id del usuario
        $this->getSession()->remove('tmp_user_id');

        return $this->render('Dinnovos\Users:Session:send_confirmation_email', array(
            'status' => $status
        ));
    }

    public function forgottenPasswordAction()
    {
        $post = $this->getPOST();
        $status = 3;
        $email = null;
        $errors = array();

        if( count( $post ) )
        {
            $Response = $this->forward('Dinnovos\Amazonas:Captcha:isValidCaptcha');

            $data = json_decode( $Response->getContent() );

            if( !isset($data->status) || ( isset($data->status) && $data->status != 'ok') )
            {
                $errors['captcha'] = 'EL c&oacute;digo de seguridad es incorrecto.';
            }

            if( array_key_exists( 'forgotten', $post ) && array_key_exists( 'email', $post['forgotten'] ) && $post['forgotten']['email'] != '' )
            {
                if( \Kodazzi\Tools\RegularExpression::isEmail( $post['forgotten']['email'] ) )
                {
                    $ModelUser = $this->getDB()->model($this->model);

                    $User = $ModelUser->fetch( array( 'email' => $post['forgotten']['email'], 'email_confirm' => 1 ) );

                    if( $User )
                    {
                        // Se verifica que no exista el errors del captcha.
                        if( count( $errors ) == 0 )
                        {
                            $email = $User->email;
                            $token = $this->createToken( $User->email );

                            $result = $ModelUser->update( array(
                                'token_forgotten' => $token,
                                'token_forgotten_created' => $this->getTimestamp()
                            ), array(
                                'id' => $User->id,
                                'status' => 1
                            ));

                            if( (int)$result === 1 )
                            {
                                if($this->sendForgottenPassword( $User, $token ))
                                {
                                    $status = 1;
                                }
                                else
                                {
                                    $status = 2;
                                }
                            }
                            else
                            {
                                $status = 2;
                            }
                        }
                    }
                    else
                    {
                        $errors['email'] = 'EL correo electr&oacute;nico no fue encontrado.';
                    }
                }
                else
                {
                    $errors['email'] = 'El correo electr&oacute;nico no es v&aacute;lido.';
                }
            }
            else
            {
                $errors['email'] = 'El correo electr&oacute;nico es requerido.';
            }
        }

        return $this->render('Dinnovos\Users:Session:forgotten_password',array(
            'status' => $status,
            'email' => $email,
            'errors' => $errors
        ));
    }

    public function modifyPasswordAction()
    {
        $token = $this->getRequest()->get('token');

        $max_hours = 1;
        $status = 1;
        $post = $this->getPOST();
        $errors = array();
        $User = null;

        if( preg_match('/^[a-zA-Z0-9]+$/', $token ) )
        {
            $ModelUser = $this->getDB()->model($this->model);

            $User = $ModelUser->fetch( array('token_forgotten' => $token, 'status' => 1 ) );

            if( $User )
            {
                $diff = \Kodazzi\Tools\Date::diff( $User->token_forgotten_created, $this->getTimestamp(), 'h' );

                // SI es mayor o igual a uno o es mejor que cero dias dice que ha expirado.
                if( ( $diff >= $max_hours ) || ( $diff < 0 ) )
                {
                    // Error 2: Token ha expirado.
                    $status = 2;
                }
                else if( (int)$User->email_confirm === 0 )
                {
                    // Error 3: Email sin confirmar.
                    $status = 3;
                }
            }
            else
            {
                // Error 2: Token no valido.
                $status = 5;
            }
        }

        if( $status === 1 && count( $post ) && array_key_exists( 'modify', $post ) && array_key_exists( 'password', $post['modify'] ) && array_key_exists( 'confirmation_password', $post['modify'] ) )
        {
            $password = $post['modify']['password'];
            $confirmation_password = $post['modify']['confirmation_password'];

            if( $password != '' && $confirmation_password != '' )
            {
                if( $password != $confirmation_password )
                {
                    $errors['password'] = 'La clave debe ser igual a la confirmaci&oacute;n';
                    $errors['confirmation_password'] = 'La confirmaci&oacute;n debe ser igual a la clave';
                }
                else if( !\Kodazzi\Tools\RegularExpression::isValidPassword( $password ) )
                {
                    $errors['password'] = 'La clave debe tener n&uacute;meros, letras en may&uacute;scula, letras en min&uacute;sculas y entre 6 y 15 caracteres.';
                }
            }
            else
            {
                if( $password == '' )
                {
                    $errors['password'] = 'La clave requerida';
                }

                if( $confirmation_password == '' )
                {
                    $errors['confirmation_password'] = 'La confirmaci&oacute;n es requerida.';
                }
            }

            if( count($errors) == 0 )
            {
                $result = $ModelUser->update( array(
                    'password' => $this->getSession()->encript( $password ),
                    'token_forgotten' => null,
                    'token_forgotten_created' => null,
                ),array(
                    'id' => $User->id,
                    'status' => 1
                ));

                if( (int)$result == 1 )
                {
                    // Status 5: CLave modificada correctamente.
                    $status = 4;
                }
            }
        }

        return $this->render('Dinnovos\Users:Session:modify_password',array(
            'status' => $status,
            'max_hours' => $max_hours,
            'errors' => $errors
        ));
    }
}