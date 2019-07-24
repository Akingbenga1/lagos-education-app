<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class ClassSubdivisions extends Model
{
    use SoftDeletes;
        /**
         * The database table used by the model.
         *
         * @var string
         */
        protected $table = 'class_subdivisions';
}
