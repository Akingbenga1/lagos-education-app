<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Social extends Model  {



 // protected $fillable = array('classname', 'subclass',  'termname', 'subjectid', 'year', 'assignedroleid', 'designation');
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	// protected $table = 'stafftable';
	// protected $primaryKey = 'stafftableid';
	//public $timestamps = false;

	// public function assignedrolesBelong()
  //   {
  //       return $this->belongsTo('AssignedRoles','assignedroleid','id');
  //   }//end relationship function subject_score and student_term

  public function UserBelong()
 {
     return $this->belongsTo('App\Models\Users','userid', 'userid');
 }//end relationship function between students and user
}
