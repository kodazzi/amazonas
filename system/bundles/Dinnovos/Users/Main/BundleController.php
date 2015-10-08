<?php

/**
* This file is part of the Kodazzi.
*
* (c) Jorge Gaitan <jgaitan@kodazzi.com>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace Dinnovos\Users\Main;

use Dinnovos\Amazonas\Main\WebBundleController;

class BundleController extends WebBundleController
{
    protected $email_no_reply = 'noreply@portal.com';
    protected $email_admin = 'info@portal.com';

    protected $template_row = 'form/user_row';
    protected $model = 'Dinnovos\Users\Models\UserModel';

    protected $need_approval = true;
    protected $start_after_registration = false;
    protected $max_days_unconfirme_email = 3;
    protected $max_days_token = 2;
    protected $project = 'Portal';

    protected $route_landing = 'user-dashboard';

    protected $title = 'Usuarios';

    public function preAction()
    {
        parent::preAction();

        $this->need_approval = $this->getSetting('DS-NEED-APPROVAL', $this->need_approval);
        $this->start_after_registration = $this->getSetting('DS-START-AFTER-REGISTRATION', $this->start_after_registration);
        $this->max_days_unconfirme_email = $this->getSetting('DS-MAX-DAYS-UNCONFIRME-EMAIL', $this->max_days_unconfirme_email);
        $this->project = $this->getSetting('DS-NAME-PROJECT', $this->project);
        $this->email_admin = $this->getSetting('DS-EMAIL', $this->email_admin);
    }

    protected function sendForgottenPassword( $User, $token )
    {
        if( $User )
        {
            $View = $this->getView();

            $data = array(
                'project' 	    => $this->project,
                'base_url' 		=> $this->getBaseUrl(),
                'server'        => $_SERVER,
                'user'          => $User,
                'token' 		=> $token
            );

            $content = $View->render('Dinnovos\Users:Session:email_forgotten_password', $data);

            $PHPMailer = $this->getPHPMailer();
            $PHPMailer->setFrom($this->email_no_reply, "{$this->project}");
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

    protected function sendTokenConfirmationEmail( $user_id )
    {
        $User = $this->getDB()->model($this->model)->fetch( array('id' => $user_id ) );

        if( $User )
        {
            $View = $this->getView();

            $data = array(
                'project' 	=> $this->project,
                'base_url' 	=> $this->getBaseUrl(),
                'user'      => $User,
                'token'     => $User->token_email
            );

            $content = $View->render('Dinnovos\Users:Registration:email_confirmation', $data);

            $PHPMailer = $this->getPHPMailer();
            $PHPMailer->setFrom($this->email_no_reply, "{$this->project}");
            $PHPMailer->addAddress($User->email);
            $PHPMailer->Subject = "Confirmacion de E-mail";
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

    protected function sendWelcomeAndTokenConfirmation($user_id)
    {
        $User = $this->getDB()->model($this->model)->fetch(array('id' => $user_id));

        if( $User )
        {
            $View = $this->getView();

            $data = array(
                'project' 	    => $this->project,
                'base_url' 		=> $this->getBaseUrl(),
                'user'          => $User,
                'token'         => $User->token_email
            );

            $content = $View->render('Dinnovos\Users:Registration:email_confirmation', $data);

            $PHPMailer = $this->getPHPMailer();
            $PHPMailer->setFrom($this->email_no_reply, "{$this->project}");
            $PHPMailer->addAddress($User->email);
            $PHPMailer->Subject = "Confirmacion de E-mail";
            $PHPMailer->msgHTML($content);

            try
            {
                if(!$PHPMailer->send())
                {
                    return false;
                }
            }
            catch (\Exception $e)
            {
                return false;
            }

            $PHPMailer->clearAllRecipients();
            $PHPMailer->clearAddresses();

            //------------------------------------------------------------------------------------

            $data = array(
                'project' 	    => $this->project,
                'base_url' 		=> $this->getBaseUrl(),
                'server'        => $_SERVER,
                'user'          => $User
            );

            $content = $View->render('Dinnovos\Users:Registration:email_registration_user', $data);

            $Report = $this->getPHPMailer();
            $Report->setFrom($this->email_no_reply, "{$this->project}");
            $Report->addAddress( $this->email_admin, 'Nuevo Registro' );
            $Report->Subject = "Nuevo Usuario: {$User->email}";
            $Report->msgHTML($content);
            $Report->send();

            try
            {
                if(!$Report->send())
                {
                    return false;
                }
            }
            catch (\Exception $e)
            {
                return false;
            }
        }

        return true;
    }

    /*
     * Envia un email cuando la cuenta de correo ha sido confirmada
     */
    protected function sendEmailVerified( $User )
    {
        if( $User )
        {
            $View = $this->getView();

            $data = array(
                'project' 	    => $this->project,
                'base_url' 		=> $this->getBaseUrl(true)
            );

            $content = $View->render('Dinnovos\Users:Registration:email_verified', $data);

            $PHPMailer = $this->getPHPMailer();
            $PHPMailer->setFrom($this->email_no_reply, "{$this->project}");
            $PHPMailer->addAddress( $User->email );
            $PHPMailer->Subject = "Cuenta de correo confirmada";
            $PHPMailer->msgHTML( $content );

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

    //-----------------------------------------------------------------------------------------------------------------

    protected function createUserCard($User)
    {
        if($User)
        {
            $UserCardManager = $this->getUserCardManager();

            $Card = $UserCardManager->getNewCard();
            $Card->setUser($User->username);

            $Card->setRole('USER');

            $Card->setAttributes(array(
                'id'             => $User->id,
                'email'          => $User->email,
                'first_name'     => $User->first_name,
                'last_name'      => $User->last_name,
                'user_agent'     => $this->getSession()->createTokenSession(),
                'last_logging'   => $User->last_logging
            ));

            $UserCardManager->add($Card);

            return true;
        }

        return false;
    }

    protected function searchUser($username, $password)
    {
        $password = $this->getSession()->encript($password);
        $Model = \Service::get('db')->model($this->model);

        $where = array(
            'username'      => $username,
            'password'      => $password
        );

        return $Model->fetch($where);
    }

    protected function isValidEmail($User)
    {
        if($User)
        {
            if( (int)$User->email_confirm === 1 )
            {
                return true;
            }

            if( (int)$User->email_confirm === 0 )
            {
                if($this->start_after_registration)
                {
                    $diff = \Kodazzi\Tools\Date::diff( $User->created, $this->getTimestamp(), 'd' );

                    if( $diff > 0 && $diff < $this->max_days_unconfirme_email)
                    {
                        return true;
                    }
                }
            }
        }

        return false;
    }
}