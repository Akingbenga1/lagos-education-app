<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StudentRegistration extends Model {

    use SoftDeletes;

	protected $table = 'student_registration';
	protected $primaryKey = 'id';



}