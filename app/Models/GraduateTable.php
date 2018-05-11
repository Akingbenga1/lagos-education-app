<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GraduateTable extends Model  {
	



 protected $fillable = array('studentid', 'createdby'); 
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'graduatetable';
	protected $primaryKey = 'graduatetableid';
	//public $timestamps = false;

 /*
	public function questionOptionsBelong()
    {
        return $this->belongsTo('QuestionOptions','questionoptionsid','questionoptionsid');
    }//end relationship function subject_score and student_term

    public function questionAnswerBelong()
    {
        return $this->belongsTo('QuestionAnswers','questionanswerid','questionanswerid');
    }//end relationship function subject_score and student_term
     */





}