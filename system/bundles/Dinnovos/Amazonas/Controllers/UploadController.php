<?php

namespace Dinnovos\Amazonas\Controllers;

use Kodazzi\Controller;
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Filesystem\Filesystem;

class UploadController extends Controller
{
    protected $max_size_images = 2048;
    protected $min_width_images = 600;
    protected $min_height_images = 350;
    protected $allowed_types_regular_expression = '/(\.jpg|\.jpeg|\.png)$/';
    protected $allowed_types = array('jpg', 'jpeg', 'png');
    protected $path = '/upload/';
    protected $path_tmp = '/tmp';

    protected $isSimple = false;

    public function preAction() { }

    public function displayAction()
    {
        $post = $this->getPOST();
        $files = array();
        $path = trim($this->path, '/').'/';

        if(!is_dir(YS_PUBLIC.$path))
        {
            return $this->jsonResponse(array('status'=>'failed','msg'=>'Disculpe el directorio no fue encontrado.'));
        }

        $finder = new Finder();
        $finder->files()->name($this->allowed_types_regular_expression)->in(YS_PUBLIC.$path);

        // Une todos los esquemas en un solo array
        foreach( $finder as $file )
        {
            // Concatena el esquema de cada archivo conseguido
            $files[] = '/'.$path.$file->getRelativePathname();
        }

        return $this->jsonResponse(array('status'=>'ok','files'=>$files));
    }

    public function uploadAction()
    {
        $allowed_types = $this->allowed_types;
        $max_size = $this->max_size_images;
        $path = trim($this->path, '/').'/';

        if($this->isSimple)
        {
            $path = trim($this->path_tmp, '/').'/';

            $this->mkdir($path);
        }

        $files = $this->getRequest()->files->all();

        $UploadedFile = ( array_key_exists('file', $files) ) ? $files['file'] : null;

        if( $UploadedFile && $UploadedFile instanceof UploadedFile )
        {
            $originalName = $UploadedFile->getClientOriginalName();
            $extension = strtolower( $UploadedFile->getClientOriginalExtension() );
            $size = $UploadedFile->getClientSize();

            if ( !in_array( $extension, $allowed_types ) )
            {
                return $this->jsonResponse(array('status' => 'failed', 'msg' => '<p class="text-danger"> El tipo de archivo seleccinado no es v&aacute;lido.</p>'));
            }

            if ( $size > (int)$max_size * 1024 )
            {
                return $this->jsonResponse(array('status' => 'failed', 'msg' => '<p class="text-danger"> La imagen no puede ser mayor a ('.$max_size.')Kb.</p>'));
            }

            // Sustituye todo lo que no se alfanumerico por guion
            $newName = preg_replace('/[^\.a-zA-Z0-9]+/', '-', strtolower($originalName));

            // Si es simple le agrega al nombre el timestamp para que sea unico en el directorio temporal
            if($this->isSimple)
            {
                $newName = preg_replace('/[^0-9]+/', '', $this->getTimestamp()).'-'.$newName;
            }
            else
            {
                $prefix = '';

                // Crea el path para subir la imagen
                $this->mkdir(YS_PUBLIC.$path);

                $i = 1;

                while(is_file(YS_PUBLIC.$path.$prefix.$newName))
                {
                    // Si en 20 intentos aun existe una imagen con el mismo nombre se concatena el datetime actual
                    if($i == 21)
                    {
                        $prefix = '';
                        $newName = preg_replace('/[^0-9]+/', '', $this->getTimestamp()).'-'.$newName;
                        break;
                    }

                    $prefix = "$i-";
                    $i++;
                }

                $newName = $prefix.$newName;
            }

            $target = $UploadedFile->move(YS_PUBLIC.$path, $newName );

            if($target)
            {
                // Si el upload es simple se almacena los datos del archivo temporal en la sesion.
                if($this->isSimple)
                {
                    $old_image = $this->getSession()->get('upload');

                    // Si encuentra una imagen vieja en la sesion la elimina antes de almacenar una nueva.
                    if(is_array($old_image) && array_key_exists('temporary_path', $old_image) && is_file($old_image['temporary_path']))
                    {
                        unlink($old_image['temporary_path']);
                    }

                    $this->getSession()->set('upload', array(
                        'temporary_path'    => YS_PUBLIC.$path.$newName,
                        'name'              => $newName,
                    ));
                }

                return $this->jsonResponse(array('status' => 'ok', 'path_http' => '/'.$path.$newName, ));
            }
        }

        return $this->jsonResponse(array('status' => 'failed', 'msg' => 'Ocurrio un error al subir la imagen, por favor intente nuevamente.'));
    }

    public function renameAction()
    {
        $post = $this->getPOST();

        if((array_key_exists('k-file-new', $post) && $post['k-file-new'] != '') && (array_key_exists('k-file-old', $post) && $post['k-file-old'] != ''))
        {
            if(is_file(YS_PUBLIC.YS_UPLOAD.$post['k-file-old']))
            {
                $name = $post['k-file-new'];

                if($post['k-file-old'] == $name)
                {
                    return $this->jsonResponse(array('status'=>'failed', 'msg'=>'No ha modificado el nombre.'));
                }

                if(is_file(YS_PUBLIC.YS_UPLOAD.$name))
                {
                    return $this->jsonResponse(array('status'=>'failed', 'msg'=>'Ya existe un archivo con el mismo nombre.'));
                }

                if(rename(YS_PUBLIC.YS_UPLOAD.$post['k-file-old'], YS_PUBLIC.YS_UPLOAD.$name))
                {
                    return $this->jsonResponse(array('status'=>'ok', 'name'=>$name, 'msg'=>'Se ha renombrado el archivo correctamente'));
                }
            }
        }

        return $this->jsonResponse(array('status'=>'failed', 'msg'=>'Ocurrio un error, intente nuevamente.'));
    }

    public function deleteAction()
    {
        $post = $this->getPOST();

        if((array_key_exists('k-file-old', $post) && $post['k-file-old'] != ''))
        {
            if(is_file(YS_PUBLIC.YS_UPLOAD.$post['k-file-old']))
            {
                unlink(YS_PUBLIC.YS_UPLOAD.$post['k-file-old']);

                return $this->jsonResponse(array('status'=>'ok', 'msg'=>'Se ha eliminado correctamente'));
            }
        }

        return $this->jsonResponse(array('status'=>'failed', 'msg'=>'Ocurrio un error, intente nuevamente.'));
    }

    protected function upload()
    {
        $allowed_types = $this->allowed_types;
        $max_size = $this->max_size_images;

        $path = trim($this->path, '/').'/';

        if(!is_dir(YS_PUBLIC.$path))
        {
            return $this->jsonResponse(array('status'=>'failed','msg'=>'Disculpe el directorio no fue encontrado.'));
        }

        $files = $this->getRequest()->files->all();

        $UploadedFile = ( array_key_exists('file', $files) ) ? $files['file'] : null;

        if( $UploadedFile && $UploadedFile instanceof UploadedFile )
        {
            $originalName = $UploadedFile->getClientOriginalName();
            $extension = strtolower( $UploadedFile->getClientOriginalExtension() );
            $size = $UploadedFile->getClientSize();

            if( !in_array( $extension, $allowed_types ) )
            {
                return $this->jsonResponse(array('status' => 'failed', 'msg' => '<p class="text-danger"> El tipo de archivo seleccinado no es v&aacute;lido.</p>'));
            }

            if ( $size > (int)$max_size * 1024 )
            {
                return $this->jsonResponse(array('status' => 'failed', 'msg' => '<p class="text-danger"> La imagen no puede ser mayor a ('.$max_size.')Kb.</p>'));
            }

            // Sustituye todo lo que no se alfanumerico por guion
            $newName = preg_replace('/[^\.a-zA-Z0-9]+/', '-', strtolower($originalName));
            $prefix = '';

            // Crea el path para subir la imagen
            $this->mkdir(YS_PUBLIC.$path);

            $i = 1;

            while(is_file(YS_PUBLIC.$path.$prefix.$newName))
            {
                // Si en 20 intentos aun existe una imagen con el mismo nombre se concatena el datetime actual
                if($i == 21)
                {
                    $prefix = '';
                    $newName = preg_replace('/[^0-9]+/', '', $this->getTimestamp()).'-'.$newName;
                    break;
                }

                $prefix = "$i-";
                $i++;
            }

            $newName = $prefix.$newName;

            $target = $UploadedFile->move(YS_PUBLIC.$path, $newName );

            if($target)
            {
                return $this->jsonResponse(array('status' => 'ok', 'path_http' => '/'.$path.$newName, ));
            }
        }

        return $this->jsonResponse(array('status' => 'failed', 'msg' => 'Ocurrio un error al subir la imagen, por favor intente nuevamente.'));
    }

    protected function mkdir( $path, $type_response = 'json' )
    {
        $fs = new fileSystem();

        try
        {
            $fs->mkdir( $path );

            return  true;
        }
        catch (IOException $e)
        {
            if( $type_response == 'json' )
            {
                exit( json_encode(array('status' => 'failed', 'msg' => 'Ocurrio un error interno, por favor intente mas tarde.')));
            }
        }

        return false;
    }
}