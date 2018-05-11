<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuestionOptions extends Model  {
	



 protected $fillable = array('optionA', 'optionB', 'optionC', 'optionD', 'createdby', 'modifiedby'); 
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'questionoptions';
	protected $primaryKey = 'questionoptionsid';
	//public $timestamps = false;

}