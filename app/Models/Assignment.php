<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Assignment extends Model
{
    use HasFactory;

    protected $fillable =[
        'title',
        'description',
        'mark',
        'course_id',
        'subject_id',
        'user_id',
        'date',
        'file'
    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function subject(){
        return $this->belongsTo(Subject::class);
    }

    public function course():BelongsTo
    {
        return $this->belongsTo(Course::class);
    }
    public function student_assignment(): HasMany
    {
        return $this->hasMany(StudentAssignment::class);
    }


}
