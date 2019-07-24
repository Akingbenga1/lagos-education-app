<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Students extends Model  {

    use SoftDeletes;



//protected $fillable = array('school_admission_number', 'userid');

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'students';

	/**
	 * The attributes excluded from the model's JSON form.
	 *\
	 * @var array
	 */

	/**
	 * Get the unique identifier for the user.
	 *
	 * @return mixed
	 */


	 public function StudentTermRelate()
    {
        return $this->hasMany('App\Models\StudentTerm','studentid', 'studentid');
    }//end relationship function between students and studentpassword

     public function UserBelong()
    {
        return $this->belongsTo('App\Models\Users','userid', 'userid');
    }//end relationship function between students and user

}
