<?php

namespace Dinnovos\Amazonas\Controllers;

use Kodazzi\Controller;

class CaptchaController extends Controller
{
    public function showAction()
    {
		$Session = $this->getSession();

		// Set to whatever size you want, or randomize for more security

		$captchaTextSize = 5;

		do {

			// Generate a random string and encrypt it with md5

			$md5Hash = md5( date('Y-m-d h:m:s', time() ) );

			// Remove any hard to distinguish characters from our hash

			preg_replace( '([1aeilou0])', "", $md5Hash );

		} while( strlen( $md5Hash ) < $captchaTextSize );

		// we need only 7 characters for this captcha

		$key = substr( $md5Hash, 0, $captchaTextSize );

		// Add the newly generated key to the session. Note, it is encrypted.

        $Session->set('captcha', $Session->encript( $key ));

		// grab the base image from our pre-generated captcha image background

		$captchaImage = imagecreatefrompng( "src/captcha/images/captcha.png" );

		/*

		Select a color for the text. Since our background is an aqua/greenish color, we choose a text color that will stand out, but not completely. A slightly darker green in our case.

		*/

		$textColor = imagecolorallocate( $captchaImage, 40, 106, 160 );

		/*

		Select a color for the random lines we want to draw on top of the image, in this case, we are going to use another shade of green/blue

		*/

		$lineColor = imagecolorallocate( $captchaImage, 47, 105, 157 );

		// get the size parameters of our image

		$imageInfo = getimagesize( "src/captcha/images/captcha.png" );

		// decide how many lines you want to draw

		$linesToDraw = 10;

		// Add the lines randomly to the image

		for( $i = 0; $i < $linesToDraw; $i++ )
		{
			// generate random start spots and end spots

			$xStart = mt_rand( 0, $imageInfo[ 0 ] );
			$xEnd = mt_rand( 0, $imageInfo[ 0 ] );

			// Draw the line to the captcha

			imageline( $captchaImage, $xStart, 0, $xEnd, $imageInfo[1], $lineColor );
		}

		/*
		Draw our randomly generated string to our captcha using the given true type font. In this case, I am using BitStream Vera Sans Bold, but you could modify it to any other font you wanted to use.
		*/

		imagettftext( $captchaImage, 28, 0, 38, 38, $textColor, "src/captcha/fonts/VeraBd.ttf", $key );

		// Output the image to the browser, header settings prevent caching

		header ( "Content-type: image/png" );

		header("Cache-Control: no-cache, must-revalidate");
		header("Expires: Fri, 19 Jan 1994 05:00:00 GMT");
		header("Pragma: no-cache");

		imagepng( $captchaImage );

        exit;
	}

	public function isValidCaptchaAction()
	{
		$post = $this->getPOST();

		if( isset($post['code_captcha']) && $post['code_captcha'] != '' )
		{
			$captcha = $this->getSession()->encript( $this->clear( $post['code_captcha'] ) );

			if( $captcha === $this->getSession()->get( 'captcha', -1 ) )
			{
				return $this->jsonResponse( array( 'status' => 'ok' ) );
			}
		}

		return $this->jsonResponse( array( 'status' => 'failed' ) );
	}
}