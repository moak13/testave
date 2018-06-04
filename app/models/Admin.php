<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
	protected $table = 'admins';
	protected $created_at = 'date_added';
	protected $updated_at ='date_modified';

	protected $fillable = [
		'email',
		'name',
		'password',
		'admin_id', //thinking of either generating one with a code or using their matric no there
		'activationLink',
		'activated',
		'clearText',
	];
}

?>