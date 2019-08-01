<?php

namespace App\Models;
//use Illuminate\Auth\UserInterface;
//use Illuminate\Auth\Reminders\RemindableInterface;
//use Illuminate\Auth\Reminders\RemindableTrait;
//use Zizaco\Entrust\HasRole;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;

class Users   extends Authenticatable /*implements UserInterface ,RemindableInterface */{
//	use RemindableTrait,  HasRole;

use SoftDeletes;



protected $fillable = array('useremail', 'password','firstname', 'surname', 'middlename','activated', 'date_of_birth'); 
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'users';
	protected $primaryKey = 'id';

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = array('password');

	/**
	 * Get the unique identifier for the user.
	 *
	 * @return mixed
	 */

	public function StudentRelate()
    {
        return $this->hasOne('App\Models\Students','userid','userid');
    }//end relationship function students and user


	public function AssignedRoleRelate()
    {
        return $this->hasMany('App\Models\AssignedRoles','user_id','userid');
    }//end relationship function students and user



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
	public function getRememberToken()
{
    return $this->remember_token;   
}

public function setRememberToken($value)
{
    $this->remember_token = $value;
} 

public function getRememberTokenName()
{
    return 'remember_token';
}


	
	

}