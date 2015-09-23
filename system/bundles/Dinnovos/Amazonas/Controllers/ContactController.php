<?php

namespace Dinnovos\Amazonas\Controllers;

use Dinnovos\Amazonas\Main\WebBundleController;

class ContactController extends WebBundleController
{
    public function showAction()
    {
        $Form = $this->getForm('Dinnovos\Amazonas\Forms\MessageForm');

        $redirect = $this->saveForm( $Form );

        if( $redirect === true )
        {
            return $this->redirectResponse( $this->buildUrl('contact') );
        }

        return $this->render('@web\Dinnovos\Amazonas:Contact:show', array(
            'page' => ''
        ));
    }

    private function saveForm( \Kodazzi\Form\FormBuilder $Form )
    {
        $View = $this->getView();
        $post = $this->getPOST();
        $errors = array();

        if( is_array( $post ) && count( $post ) )
        {
            $Response = $this->forward('Dinnovos\Amazonas:Captcha:isValidCaptcha', array(), array(), $post );

            $data = json_decode( $Response->getContent() );

            if( !isset($data->status) || ( isset($data->status) && $data->status != 'ok') )
            {
                $errors['captcha'] = 'EL c&oacute;digo de seguridad es incorrecto.';
            }

            $name_form = $Form->getNameForm();

            $post[$name_form]['status'] = 0;

            $Form->bind( $post );

            if($Form->isValid() && count($errors) == 0)
            {
                $result = $Form->save();

                if( $result )
                {
                    $this->getView()->msgSuccess('Su mensaje fue enviando correctamente.');

                    try{
                        $this->sendContact($post[$name_form]);
                    }catch (\Exception $a){}


                    return true;
                }
                else
                {
                    $this->getView()->msgError('Ocurrio un error al enviar el mensaje, por favor intente m&aacute;s tarde');
                }
            }
            else
            {
                $this->getView()->msgError('El formulario tiene algunos errores.');
            }
        }

        $View->set( array(
            'form' =>$Form,
            'errors' => $errors
        ) );
    }

    protected function sendContact( $post )
    {
        if( is_array( $post ) && count( $post ) )
        {
            $View = $this->getView();
            $project = $this->getSetting('DS-NAME-PROJECT');
            $email_admin = $this->getSetting('DS-EMAIL');

            $data = array(
                'project' 	=> $project,
                'base_url' 	=> $this->getBaseUrl(),
                'subject'   => "Contacto desde {$project}"
            );

            $content = $View->render('@web\Dinnovos\Amazonas:Contact:email_user', $data);

            $PHPMailer = $this->getPHPMailer();
            $PHPMailer->From = 'noreply@domain.com';
            $PHPMailer->FromName = "{$project}";
            $PHPMailer->AddAddress( $post['email'] );
            $PHPMailer->Subject = "Contacto desde {$project}";
            $PHPMailer->msgHTML( $content );
            $PHPMailer->Send();

            $data = array(
                'name_project' 	=> $project,
                'base_url' 		=> $this->getBaseUrl(),
                'data'          => $post
            );

            $content = $View->render('@web\Dinnovos\Amazonas:Contact:email_admin', $data);

            $Report = $this->getPHPMailer();
            $Report->From = $post['email'];
            $Report->FromName = "Contacto - {$post['fullname']}";
            $Report->AddAddress($email_admin);
            $Report->Subject = "Formulario de Contacto {$project}";
            $Report->msgHTML($content);
            $Report->Send();
        }
    }
}