<?php

namespace App\Controllers\AdminAuth;

use App\Models\Admin;
use App\Auth\AuthAdmin;
use App\Controllers\BaseController;

class AdminAuthController extends BaseController
{
	public function getSignUp($request, $response)
	{
		return $this->container->view->render($response, 'templates/admin/auth/signup.twig');
	}
	public function postSignUp($request, $response)
	{
		$token = $this->AuthAdmin->random_char();
		$secret = passwordHash::hash($token);

		$admin = Admin::create([
			'email' => $request->getParam('email'),
			'name' => $request->getParam('name'),
			'password' => $secret,
			'clearText' => $token,
		]);
		return $response->withRedirect($this->router->pathFor('admin_auth.signin'));
	}
	public function getSignIn($request, $response)
	{
		return $this->container->view->render($response, 'templates/admin/auth/signin.twig');
	}
	public function postSignIn($request, $response)
	{
		$auth = $this->AuthAdmin->tryAttempt(
			$request->getParam('email'),
			$request->getParam('password')
		);
		if(!$auth){
			return $response->withRedirect($this->router->pathFor('admin_auth.signin'));
		}
		return $response->withRedirect($this->router->pathFor('dashboard'));
	}
	public function SendActivationLink($request, $response)
	{
		$verifiedAdmin = $this->AuthAdmin->checkMail();
		return $this->container->view->render($response, 'templates/admin/auth/activate.twig');
	}
	public function getSignOut($request, $response)
	{
		$this->AuthAdmin->logOut();
		return $response->withRedirect($this->router->pathFor('admin_auth.signin'));
	}
}

?>