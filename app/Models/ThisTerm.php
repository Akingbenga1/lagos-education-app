<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
class ThisTerm extends Model  {
	



//protected $fillable = array('studentid', 'password'); 
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'thisterm';
	protected $primaryKey = 'id';
	public $timestamps = false;

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	//protected $hidden = array('password');

	public function StudentTermRelate()
    {
        return $this->hasMany('App\Models\StudentTerm','thistermid','id');
    }//end relationship function thisterm and studentterm

}