<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class SubjectScore extends Model  {

    use SoftDeletes;
	protected $table = 'subject_score';

}