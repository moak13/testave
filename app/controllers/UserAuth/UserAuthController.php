<?php

namespace App\Controllers\UserAuth;

use App\Controllers\BaseController;
use Respect\Validation\Validator as v;

class UserAuthController extends BaseController
{
	public function getSignUp($request, $response)
	{
		return $this->container->view->render($response, 'templates/user/auth/signup.twig');
	}
	public function postSignUp($request, $response)
	{
		$validation = $this->validator->validate($request, [
			'regno' => v::noWhitespace()->notEmpty(),
		]);

		if($validation->failed()) {
			return $response->withRedirect($this->router->pathFor('user_auth.signup'));
		}

		$user = User::create([
			'regno' =>$request->getParam('regno'),
			'usertype' =>$request->getParam('usertype'),
		]);
		return $response->withRedirect($this->router->pathFor('user_auth.signin'));
	}
	public function getSignIn($request, $response)
	{
		return $this->container->view->render($response, 'templates/user/auth/signin.twig');
	}
	public function postSignIn()
	{
		$auth = $this->AuthUser->tryAttempt(
			$request->getParam('regno'),
			$request->getParam('password')
		);
		if(!$auth){
			return $response->withRedirect($this->router->pathFor('user_auth.signin'));
		}
		return $response->withRedirect($this->router->pathFor('home'));
	}
	public function getSignOut($request, $response)
	{
		$this->AuthUser->logOut();
		return $response->withRedirect($this->router->pathFor('user_auth.signin'));
	}
}

?>