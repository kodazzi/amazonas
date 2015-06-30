<?php

namespace Dinnovos\Amazonas\Controllers;

use Dinnovos\Amazonas\Main\BundleController;

class SessionController extends BundleController
{
    public function loginAction()
    {
        $post = $this->getPOST();
        $I18n = \Service::get('translator');
        $errors = array();

        if(count($post))
        {
            $user = (array_key_exists('_user', $post) && $post['_user'] != '') ? $post['_user'] : null;
            $password = (array_key_exists('_password', $post) && $post['_password'] != '') ? $post['_password'] : null;

            if( !$user )
            {
                $errors['_user'] = $I18n->get('user.user_required');
            }

            if( !$password )
            {
                $errors['_password'] = $I18n->get('user.password_required');
            }

            if(count($errors) == 0)
            {
                // Verifica en la base de datos.
                $User = $this->validateUser($user, $password);

                if( $User )
                {
                    $this->getView()->msgSuccess($I18n->get('amazonas_config.welcome')." {$User->first_name} {$User->last_name}");

                    return $this->redirectResponse($this->buildUrl('panel-dashboard'));
                }
                else
                {
                    $errors['global'] = $I18n->get('user.login_failed');
                }
            }
        }

        return $this->render('Dinnovos\Amazonas:Session:login', array(
            'errors' => $errors
        ));
	}

    private function validateUser($user, $password)
    {
        $password = $this->getUser()->encript($password);

        $User = $this->getUser();

        $where = array(
            'login'     => $user,
            'password'  => $password,
            'status'    => 1,
        );

        $UserModel = \Service::get('db')->model('Dinnovos\Amazonas\Models\UserModel')->fetch($where);

        if($UserModel)
        {
            $User->set('user_id',          $UserModel->id);
            $User->set('email',            $UserModel->email);
            $User->set('first_name',       $UserModel->first_name);
            $User->set('last_name',        $UserModel->last_name);
            $User->set('user_agent',       $User->createTokenSession());
        }

        return $UserModel;
    }
}