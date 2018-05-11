<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AssignedRoles extends Model  {
	

	protected $fillable = array('user_id','role_id'); 
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'assigned_roles';
	//protected $primaryKey = 'userid';
	public $timestamps = false;

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */

	public function userBelong()
    {
        return $this->belongsTo('App\Models\Users','user_id', 'userid');
    }//end relationship function between students and user
    	public function roleBelong()
    {
        return $this->belongsTo('App\Models\Roles','role_id', 'id');
    }//end relationship function between students and user


}// end model Class AssignedRoles