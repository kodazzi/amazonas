<?php

namespace Dinnovos\Amazonas\Controllers;

use Dinnovos\Amazonas\Main\BundleController;
use Doctrine\DBAL\Configuration;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Console\Application;
use Symfony\Component\HttpFoundation\Request;

class InstallController extends BundleController
{
    public function step1Action()
    {
        if($this->isAjax())
        {
            return $this->jsonResponse($this->step1());
        }

        return $this->render('Dinnovos\Amazonas:Install:step1', array(

        ));
	}

    public function step2Action()
    {
        if($this->isAjax())
        {
            return $this->jsonResponse($this->step2());
        }

        return $this->render('Dinnovos\Amazonas:Install:step1', array(

        ));
    }

    private function step1()
    {
        $post = $this->getPOST();

        $errors = false;
        $config = array();

        $fields = array(
            0 => array('id' => 'install_db', 'status' => 'ok'),
            1 => array('id' => 'install_user', 'status' => 'ok'),
            2 => array('id' => 'install_password', 'status' => 'ok'),
            3 => array('id' => 'install_driver', 'status' => 'ok'),
            4 => array('id' => 'install_prefix', 'status' => 'ok'),
        );

        if(array_key_exists('Install', $post) && is_array($post['Install']))
        {
            $config = $post['Install'];

            if(!array_key_exists('db', $config) || (array_key_exists('db', $config) && $config['db'] == ''))
            {
                $fields[0] = array('id'=> 'install_db', 'error' =>'El campo es necesario');
                $errors = true;
            }

            if(!array_key_exists('user', $config) || (array_key_exists('user', $config) && $config['user'] == ''))
            {
                $fields[1] = array('id'=>'install_user', 'error' =>'El campo es necesario');
                $errors = true;
            }

            if(!array_key_exists('password', $config))
            {
                $fields[2] = array('id'=>'install_password', 'error'=>'El campo no es v&aacute;lido');
                $errors = true;
            }

            if(array_key_exists('driver', $config) && $config['driver'] != '')
            {
                if(!in_array($config['driver'], array(
                    'pdo_mysql',
                    'drizzle_pdo_mysql',
                    'mysqli',
                    'pdo_sqlite',
                    'pdo_pgsql',
                    'pdo_oci',
                    'pdo_sqlsrv',
                    'sqlsrv',
                    'oci8',
                    'sqlanywhere'
                )))
                {
                    $fields[3] = array('id' => 'install_driver', 'error'=>'El motor seleccionado no es v&aacute;lido');
                    $errors = true;
                }
            }
            else
            {
                $fields[3] = array('id' => 'install_driver', 'error'=>'El campo es necesario');
                $errors = true;
            }

            if(!array_key_exists('prefix', $config) || (array_key_exists('prefix', $config) && $config['prefix'] == ''))
            {
                $fields[4] = array('id'=>'install_prefix', 'error' =>'El campo es necesario');
                $errors = true;
            }
        }
        else
        {
            return array('status' => 'failed', 'msg' => 'Ocurrio un error inesperado, intente nuevamente.');
        }

        if($errors)
        {
            return array('status' => 'failed', 'msg' => 'Se encontraron algunos errores.', 'fields' => $fields);
        }

        $connectionOptions = array(
            'dbname'    => $config['db'],
            'user'      => $config['user'],
            'password'  => $config['password'],
            'host'      => 'localhost',
            'driver'    => $config['driver'],
        );

        $configuration = new Configuration();

		$conn = \Doctrine\DBAL\DriverManager::getConnection($connectionOptions, $configuration);

        try
        {
            $conn->connect();

            $this->getSession()->set('config', $config);

            return array('status' => 'ok', 'html' => $this->getRender('Dinnovos\Amazonas:Install:step2'));

        }
        catch(\Exception $e)
        {
            return array('status' => 'failed', 'msg' => 'No logramos conectarnos, tal vez algo est&eacute; mal.');
        }
    }

    private function step2()
    {
        $post = $this->getPOST();
        $errors = false;
        $config = array();

        $fields = array(
            0 => array('id' => 'install_project', 'status' => 'ok'),
            1 => array('id' => 'install_admin', 'status' => 'ok'),
            2 => array('id' => 'install_password', 'status' => 'ok'),
            3 => array('id' => 'install_confirmation', 'status' => 'ok'),
            4 => array('id' => 'install_email', 'status' => 'ok')
        );

        if(array_key_exists('Install', $post) && is_array($post['Install']))
        {
            $config = $post['Install'];
            $password = null;
            $confirmation = null;

            if(!array_key_exists('project', $config) || (array_key_exists('project', $config) && $config['project'] == ''))
            {
                $fields[0] = array('id'=> 'install_project', 'error' =>'El campo es necesario');
                $errors = true;
            }

            if(!array_key_exists('admin', $config) || (array_key_exists('admin', $config) && $config['admin'] == ''))
            {
                $fields[1] = array('id'=>'install_admin', 'error' =>'El campo es necesario');
                $errors = true;
            }

            if(!array_key_exists('email', $config) || (array_key_exists('email', $config) && $config['email'] == ''))
            {
                $fields[2] = array('id'=>'install_email', 'error' =>'El campo es necesario');
                $errors = true;
            }

            if(!array_key_exists('password', $config) || (array_key_exists('password', $config) && $config['password'] == ''))
            {
                $fields[4] = array('id'=>'install_password', 'error'=>'El campo es necesario');
                $errors = true;
            }
            else
            {
                $password = $config['password'];
            }

            if(!array_key_exists('confirmation', $config) || (array_key_exists('confirmation', $config) && $config['confirmation'] == ''))
            {
                $fields[5] = array('id'=>'install_confirmation', 'error'=>'El campo es necesario');
                $errors = true;
            }
            else
            {
                $confirmation = $config['confirmation'];
            }

            if($password && $confirmation && ($password != $confirmation))
            {
                $fields[6] = array('id'=>'install_password', 'error'=>'La clave debe ser igual a la confirmaci&oacute;n');
                $fields[7] = array('id'=>'install_confirmation', 'error'=>'La confirmaci&oacute;n debe ser igual a la clave');
            }
        }
        else
        {
            return array('status' => 'failed', 'msg' => 'Ocurrio un error inesperado, intente nuevamente.');
        }

        if($errors)
        {
            return array('status' => 'failed', 'msg' => 'Se encontraron algunos errores.', 'fields' => $fields);
        }

        return $this->install($config);
    }

    private function install($config)
    {
        // Crea los archivos de configuracion.
        if($this->createFilesConfig())
        {
            $Shell = \Service::get('shell');
            $application = new Application();
            $application->add(new \Kodazzi\Console\Commands\SchemaCommand());
            $application->add(new \Kodazzi\Console\Commands\DatabaseCommand());
            $application->add(new \Kodazzi\Console\Commands\ModelCommand());
            $application->add(new \Kodazzi\Console\Commands\FormsCommand());

            $SchemaCommand = $application->find('app:schema');
            $DatabaseCommand = $application->find('app:database');
            $ModelCommand = $application->find('app:models');
            $FormsCommand = $application->find('app:forms');

            // Crea el esquema
            try
            {
                $Shell->execute($SchemaCommand, array( 'command' => $SchemaCommand->getName(), 'action'    => 'create', 'behavior'  => 'overwrite' ));
            }
            catch(\Exception $e)
            {
                return array('status' => 'failed', 'msg' => 'Ocurri&oacute; un error inesperado al crear el esquema, por favor actualice a la versi&oacute;n m&aacute;s resiente.');
            }

            // Crea la base de datos
            try
            {
                $Shell->execute($DatabaseCommand, array( 'command'   => $DatabaseCommand->getName(), 'version'   => 'current'));
            }
            catch(\Exception $e)
            {
                return array('status' => 'failed', 'msg' => 'Ocurri&oacute; un error inesperado al crear la base de datos, por favor actualice a la versi&oacute;n m&aacute;s resiente.');
            }

            //------------------------------------------------------------- Dinnovos\Amazonas -----------------------------------------------------------------
            try
            {
                $Shell->execute($ModelCommand, array( 'command'   => $ModelCommand->getName(), 'namespace' => 'Dinnovos\Amazonas', 'version'   => 'current' ));
            }
            catch(\Exception $e)
            {
                return array('status' => 'failed', 'msg' => 'Ocurri&oacute; un error inesperado al crear el modelo para Dinnovos\Amazonas, por favor actualice a la versi&oacute;n m&aacute;s resiente.');
            }

            try
            {
                $Shell->execute($FormsCommand, array( 'command'   => $FormsCommand->getName(), 'namespace' => 'Dinnovos\Amazonas', 'version'   => 'current'));
            }
            catch(\Exception $e)
            {
                return array('status' => 'failed', 'msg' => 'Ocurri&oacute; un error inesperado al crear los formularios para Dinnovos\Amazonas, por favor actualice a la versi&oacute;n m&aacute;s resiente.');
            }

            // Inserta la data en la base de datos. -------------------------------------------------------------------------------------------------------------------

            try
            {
                $this->insertNewData($config);
            }
            catch(\Exception $e)
            {
                return array('status' => 'failed', 'msg' => 'Ocurri&oacute; un error inesperado al cargar la data, por favor actualice a la versi&oacute;n m&aacute;s resiente.');
            }
        }
        else
        {
            return array('status' => 'failed', 'msg' => 'Ocurri&oacute; un error inesperado cargar la configuraci&oacute;n, por favor actualice a la versi&oacute;n m&aacute;s resiente.');
        }

        // Limpia la sesion
        $this->getSession()->clear();

        return array('status' => 'ok', 'url' => $this->buildUrl('login'));
    }

    private function createFilesConfig()
    {
        $fs = new Filesystem();
        $config = $this->getSession()->get('config');

        $config_db = $this->getRender('Dinnovos\Amazonas:Install:config_db', $config);
        $config_project = $this->getRender('Dinnovos\Amazonas:Install:config_app', array('token' => sha1($this->getTimestamp())));
        $config_routes = $this->getRender('Dinnovos\Amazonas:Install:config_routes');

        $fs->dumpFile(YS_APP.'config/db.cf.php', $config_db);
        $fs->dumpFile(YS_APP.'config/app.cf.php', $config_project);
        $fs->dumpFile(YS_APP.'config/routes.cf.php', $config_routes);

        // Se carga nuevamente la configuracion de Kodazzi
        $ConfigInstance = \Service::get('config');
        $ConfigInstance->loadConfigGlobal();

        $options = $ConfigInstance->get('db', 'dev');

        if($options['default']['dbname'] == $config['db'])
        {
            return true;
        }

        return true;
    }

    private function insertNewData($config)
    {
        $this->getDB()->model('Dinnovos\Amazonas\Models\SettingModel')->insert(array(
            'title'     => 'Nombre del Proyecto',
            'label'     => 'DS-NAME-PROYECT',
            'content'   => $config['project'],
            'help'      => '',
            'type'      => 'string',
            'created'   => $this->getTimestamp(),
            'updated'   => $this->getTimestamp(),
        ));

        $this->getDB()->model('Dinnovos\Amazonas\Models\SettingModel')->insert(array(
            'title'     => 'Correo para notificaciones',
            'label'     => 'DS-EMAIL',
            'content'   => $config['email'],
            'help'      => '',
            'type'      => 'string',
            'created'   => $this->getTimestamp(),
            'updated'   => $this->getTimestamp(),
        ));

        $this->getDB()->model('Dinnovos\Amazonas\Models\AdminModel')->insert(array(
            'first_name'    => 'Administrator',
            'last_name'     => 'Administrator',
            'email'         => $config['email'],
            'username'      => $config['admin'],
            'password'      => $this->getSession()->encript( $config['password'] ),
            'super_admin'   => '1',
            'status'        => '1',
            'created'       => $this->getTimestamp(),
            'updated'       => $this->getTimestamp(),
        ));

        $this->getDB()->model('Dinnovos\Amazonas\Models\LanguageModel')->insert(array(
            'name'          => 'Espa&ntilde;ol',
            'code'          => 'es',
            'by_default'    => '1',
        ));
    }
}