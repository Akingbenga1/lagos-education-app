<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
class TermDuration extends Model  {
	
protected $fillable = array('termname', 'classname', 'class_subdivision', 'year','studentid','createdby','termbegins',
							 'termend','nexttermbegins', ); 
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'termduration';
	protected $primaryKey = 'termdurationid';

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
    }//end relationship function between termduration and user

    public function StudentBelong()
    {
        return $this->belongsTo('App\Models\Students','studentid', 'studentid');
    }//end relationship function between termduration and student
}