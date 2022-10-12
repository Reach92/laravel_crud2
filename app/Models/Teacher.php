<?php

namespace App\Models;

use App\Models\Scopes\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Teacher extends Model
{
    use HasFactory;
    use Searchable;

    protected $fillable = ['name', 'email', 'job', 'subject_id'];

    protected $searchableFields = ['*'];

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }
}
