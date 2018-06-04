<?php 

namespace App\Auth;

use App\Models\Admin;
use App\Services\PasswordHash_config\passwordHash;

class AuthAdmin
{

	public function random_char()
	{
		// where char stands for the string u want to randomize
		$char = 'abcdefghijklmnopqrstuvwxyz1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZ!#@$%&?';
		$char_length = 5;
		$cl = strlen($char);
		$randomize = '';
		for($i = 0; $i < $char_length; $i++ ){
			$randomize .= $char[rand(0, $cl - 1)]; 
		}
		return $randomize;
	}
	

	// To create an admin
	public function signUp($email, $name) //planning on removing this part entirely and use eloquent method like that for registering users... can't come and kill myself...
	{
		echo "hello";
	}

	// To check if the admin user email exists
	public function checkMail($email) //have no clue gonself if this part is working...
	{
		$checkExistence = Admin::where('email', $email, '=', Input::get('email'))->exists();
		if(count($checkExistence) > 0)
		{
			$request->email = $email;
			$request->activationLink = 'http://fstackdev.net/auth/activate.php?email=' . $email . '&ref=' . substr(sha1(mt_rand()), 0, 22); //not written the activate.php file yet!!!
		}
	}

	// for login in the admin
	public function tryAttempt($email, $password)
	{
		$admin = Admin::where('email', $email)->first();
	}
	if(!$admin){
		return false;
	}
	if(check_password($admin->password, $password)){
		$_SESSION['admin'] = $admin->id;
		return true;
	}
	return false;

	public function logOut()
	{
		unset($_SESSION['admin']);
	}
}


?>