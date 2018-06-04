<?php

namespace App\Controllers\UserAuth;

use App\Controllers\BaseController;

class UserAuthController extends BaseController
{
	public function getSignUp($request, $response)
	{
		return $this->container->view->render($response, 'templates/user/auth/signup.twig');
	}
	public function postSignUp()
	{

	}
	public function getSignIn($request, $response)
	{
		return $this->container->view->render($response, 'templates/user/auth/signin.twig');
	}
	public function postSignIn()
	{

	}
}

?>