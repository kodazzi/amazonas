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

    private function validateUser($username, $password)
    {
        $password = $this->getSession()->encript($password);

        $Model = \Service::get('db')->model('Dinnovos\Amazonas\Models\AdminModel');

        $where = array(
            'username'      => $username,
            'password'      => $password,
            'status'        => 1,
            'super_admin'   => 1
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
               'last_logging'   => $UserModel->last_logging
            ));

            $UserCardManager->add($Card);

            $UserModel->last_logging = $this->getTimestamp();

            $Model->save($UserModel);
        }

        return $UserModel;
    }
}