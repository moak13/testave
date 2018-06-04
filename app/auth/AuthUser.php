<?php 

namespace App\Auth;

use App\Models\User;
use App\Services\PasswordHash_config\passwordHash;

class AuthUser
{   // this tries to sign in the user
	public function tryAttempt($regno, $password)
	{   // checks if the users regno is in database
		$user = User::where('regno', $regno)->first();
	}// else, returns false for that user
	if(!$user){
		return false;
	}// this tend to check if the inputted pasword matches hashed password in database
	if(check_password($user->password, $password)){
		
		//if comes back true, set the user to a session through its id
		$_SESSION['user'] = $user->id;
		return true;
	}
	return false;

	// logs out the user
	public function logOut()
	{
		unset($_SESSION['user']);
	}
}


?>