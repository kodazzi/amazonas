<?php

namespace Dinnovos\Amazonas\Controllers;

use Dinnovos\Amazonas\Main\WebBundleController;

class SessionController extends WebBundleController
{
    protected $model = 'Dinnovos\Amazonas\Models\AdminModel';
    protected $email_no_reply = 'noreply@project.com';

    public function loginAction()
    {
        $post = $this->getPOST();
        $I18n = \Service::get('translator');
        $errors = array();

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
                // Verifica en la base de datos.
                $User = $this->validateUser($username, $password);

                if($User)
                {
                    $this->getView()->msgSuccess($I18n->get('user.welcome')." {$User->first_name} {$User->last_name}");

                    return $this->redirectResponse($this->buildUrl('panel-dashboard'));
                }
                else
                {
                    $this->getView()->msgSuccess($I18n->get('user.login_failed'));
                }
            }
        }

        return $this->render('Dinnovos\Amazonas:Admin/Session:login', array(
            'errors' => $errors
        ));
	}

    public function logoutAction()
    {
        $this->getUserCardManager()->clear();

        return $this->redirectResponse($this->buildUrl('login'));
    }

    public function forbiddenAction()
    {
        return $this->render('Dinnovos\Amazonas:Admin/Session:forbidden');
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

                    $User = $ModelUser->fetch( array( 'email' => $post['forgotten']['email'], 'status' => 1 ) );

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

        return $this->render('Dinnovos\Amazonas:Admin/Session:forgotten_password',array(
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
                    // Status 5: Clave modificada correctamente.
                    $status = 4;
                }
            }
        }

        return $this->render('Dinnovos\Amazonas:Admin/Session:modify_password',array(
            'status'    => $status,
            'max_hours' => $max_hours,
            'errors'    => $errors
        ));
    }

    protected function sendForgottenPassword( $User, $token )
    {
        if( $User )
        {
            $View = $this->getView();

            $data = array(
                'project' 	    => $this->title,
                'base_url' 		=> $this->getBaseUrl(),
                'server'        => $_SERVER,
                'user'          => $User,
                'token' 		=> $token
            );

            $content = $View->render('Dinnovos\Amazonas:Admin/Session:email_forgotten_password', $data);

            $PHPMailer = $this->getPHPMailer();
            $PHPMailer->setFrom($this->email_no_reply, "{$this->title}");
            $PHPMailer->addAddress($User->email);
            $PHPMailer->Subject = "Cambio de clave";
            $PHPMailer->msgHTML($content);

            try
            {
                return $PHPMailer->send();
            }
            catch (\Exception $e)
            {
                return false;
            }
        }

        return false;
    }

    private function validateUser($username, $password)
    {
        $password = $this->getSession()->encript($password);

        $Model = \Service::get('db')->model($this->model);

        $where = array(
            'username'      => $username,
            'password'      => $password,
            'status'        => 1
        );

        $UserModel = $Model->fetch($where);

        if($UserModel)
        {
            $UserCardManager = $this->getUserCardManager();

            $Card = $UserCardManager->getNewCard();
            $Card->setUser($UserModel->email);
            $Card->setRole('ADMIN');
            $Card->setAttributes(array(
               'id'             => $UserModel->id,
               'username'       => $UserModel->username,
               'first_name'     => $UserModel->first_name,
               'last_name'      => $UserModel->last_name,
               'user_agent'     => $this->getSession()->createTokenSession(),
               'last_logging'   => $UserModel->last_logging,
               'super_admin'    => $UserModel->super_admin
            ));

            $UserCardManager->add($Card);

            $UserModel->last_logging = $this->getTimestamp();

            $Model->save($UserModel);
        }

        return $UserModel;
    }
}