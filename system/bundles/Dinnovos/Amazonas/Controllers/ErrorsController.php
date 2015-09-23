<?php

namespace Dinnovos\Amazonas\Controllers;

use Dinnovos\Amazonas\Main\WebBundleController;

class ErrorsController extends WebBundleController
{
    public function error402Action()
    {
		return $this->render( 'Dinnovos\Amazonas:Errors:error402' );
	}

	public function error404Action()
	{
		return $this->render('Dinnovos\Amazonas:Errors:error404');
	}

	public function error405Action()
	{
		return $this->render('Dinnovos\Amazonas:Errors:error405');
	}

	public function error423Action()
	{
		return $this->render('Dinnovos\Amazonas:Errors:error423');
	}
}