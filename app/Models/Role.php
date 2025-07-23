<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Role
 * 
 * @property int $id
 * @property string|null $name
 * @property string|null $guard_name
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property string|null $description
 *
 * @package App\Models
 */
class Role extends Model
{
	protected $table = 'roles';

	protected $fillable = [
		'name',
		'guard_name',
		'description'
	];
}
