<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
class QuestionTable extends Model  {
	



 protected $fillable = array('classname', 'termname', 'year', 'subjectid', 'classteacher', 'questionstatement', 'questionnumber',
 							 'questionsection', 'questionimage', 'sectioninstruction', 'questionoptionsid', 'questionanswerid', 'createdby', 'modifiedby'); 
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'questiontable';
	protected $primaryKey = 'questiontableid';
	//public $timestamps = false;

	public function questionOptionsBelong()
    {
        return $this->belongsTo('App\Models\QuestionOptions','questionoptionsid','questionoptionsid');
    }//end relationship function subject_score and student_term

    public function questionAnswerBelong()
    {
        return $this->belongsTo('App\Models\QuestionAnswers','questionanswerid','questionanswerid');
    }//end relationship function subject_score and student_term

     public function questionSubjectBelong()
    {
        return $this->belongsTo('App\Models\Subjects','subjectid','subjectid');
    }//end relationship function subject_score and student_term







}