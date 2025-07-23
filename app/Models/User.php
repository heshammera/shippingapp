<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class User
 * 
 * @property int $id
 * @property string|null $name
 * @property string|null $email
 * @property string|null $email_verified_at
 * @property string|null $password
 * @property string|null $remember_token
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property string|null $role
 * @property string|null $role_id
 * @property string|null $phone
 * @property string|null $address
 * @property Carbon|null $last_login_at
 * @property int|null $is_active
 * @property Carbon|null $expires_at
 * @property string|null $theme_color
 *
 * @package App\Models
 */
class User extends Model
{
	protected $table = 'users';

	protected $casts = [
		'last_login_at' => 'datetime',
		'is_active' => 'int',
		'expires_at' => 'datetime'
	];

	protected $hidden = [
		'password',
		'remember_token'
	];

	protected $fillable = [
		'name',
		'email',
		'email_verified_at',
		'password',
		'remember_token',
		'role',
		'role_id',
		'phone',
		'address',
		'last_login_at',
		'is_active',
		'expires_at',
		'theme_color'
	];
}
