<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subscribers extends Model
{
	protected $table = 'subscribers';
	protected $created_at = 'date_added';
	protected $updated_at ='date_modified';

	protected $fillable = [
		'email',
	];
}

?>