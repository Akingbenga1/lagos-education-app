<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StudentTerm extends Model {

    use SoftDeletes;
	



  protected $fillable = array('studentid', 'thistermid', 'class_subdivision'); 
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'student_term';
	protected $primaryKey = 'id';

     public function StudentBelong()
    {
        return $this->belongsTo('App\Models\Students','studentid', 'studentid');
    }//end relationship function between students and user

       public function ThistermBelong()
    {
        return $this->belongsTo('App\Models\ThisTerm','thistermid', 'id');
    }//end relationship function between students and user


}