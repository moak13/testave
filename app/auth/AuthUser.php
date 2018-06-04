<?php 

namespace App\Auth;

use App\Models\User;

class AuthUser
{
	public function attempt($regno, $password)
	{
		$user = User::where('regno', $regno)->first();
	}
	if(!$user){
		return false;
	}
}


?>