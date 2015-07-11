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

    private function validateUser($user, $password)
    {
        $password = $this->getSession()->encript($password);

        $where = array(
            'login'     => $user,
            'password'  => $password,
            'status'    => 1,
            'admin'     => 1
        );

        $UserModel = \Service::get('db')->model('Dinnovos\Amazonas\Models\UserModel')->fetch($where);

        if($UserModel)
        {
            $UserCardManager = $this->getUserCardManager();

            $Card = $UserCardManager->getNewCard();
            $Card->setUser($UserModel->email);
            $Card->setRole('ADMIN');
            $Card->setAttributes(array(
               'id'             => $UserModel->id,
               'username'       => $UserModel->login,
               'first_name'     => $UserModel->first_name,
               'last_name'      => $UserModel->last_name,
               'user_agent'     => $this->getSession()->createTokenSession(),
               'last_logging'   => $UserModel->last_logging
            ));

            $UserCardManager->add($Card);

            $UserModel->last_logging = $this->getTimestamp();

            \Service::get('db')->model('Dinnovos\Amazonas\Models\UserModel')->save($UserModel);
        }

        return $UserModel;
    }
}