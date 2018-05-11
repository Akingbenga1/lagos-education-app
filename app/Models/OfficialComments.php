<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OfficialComments extends Model  {
	
protected $fillable = array('termname', 'classname', 'class_subdivision', 'year','createdby','classteacher', 'classteachersignatureid', 
							 'classteacherdate','principal', 'principalsignatureid', 'principaldate', 'parentsignatureid',
							 'parentdate', 'studentid', 'createdby', 'modifiedby',); 
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'officialcomment';
	protected $primaryKey = 'officialcommentid';

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


     public function UserBelong()
    {
        return $this->belongsTo('App\Models\Users','userid', 'userid');
    }//end relationship function between officialcomments and user

    public function StudentBelong()
    {
        return $this->belongsTo('App\Models\Students','studentid', 'studentid');
    }//end relationship function between officialcomments and student

    public function classTeacherSignatureBelong()
    {
        return $this->belongsTo('App\Models\OfficialSignatures','classteachersignatureid', 'officialsignatureid');
    }//end relationship function between officialcomments and student
}