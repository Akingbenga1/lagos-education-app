<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subjects extends Model  {



	protected $fillable = array('subjectname'); 
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'subjects';
	protected $primaryKey = 'subjectid';
	protected $dates = ['deleted_at'];
	public $timestamps = false;

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */

	public function SubjectScoreRelate()
    {
        return $this->hasOne('subject_score','subjectid');
    }//end relationship function subject_score and subjects


	//protected $hidden = array('password');

	/**
	 * Get the unique identifier for the user.
	 *
	 * @return mixed
	 */
	public function getAuthIdentifier()
	{
		return $this->getKey();
	}

	/**
	 * Get the password for the user.
	 *
	 * @return string
	 */
	public function getAuthPassword()
	{
		return $this->password;
	}

	/**
	 * Get the e-mail address where password reminders are sent.
	 *
	 * @return string
	 */
	public function getReminderEmail()
	{
		return $this->useremail;
	}

}