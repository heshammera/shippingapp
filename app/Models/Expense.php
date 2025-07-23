<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Expense
 * 
 * @property int $id
 * @property string|null $title
 * @property int|null $amount
 * @property string|null $expense_date
 * @property string|null $notes
 * @property int|null $created_by
 * @property string|null $created_at
 * @property string|null $updated_at
 *
 * @package App\Models
 */
class Expense extends Model
{
	protected $table = 'expenses';

	protected $casts = [
		'amount' => 'int',
		'created_by' => 'int'
	];

	protected $fillable = [
		'title',
		'amount',
		'expense_date',
		'notes',
		'created_by'
	];
}
