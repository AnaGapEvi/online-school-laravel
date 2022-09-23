<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\Pivot;

class CourseUser extends Pivot
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'course_id'
    ];

    public function courses(): BelongsToMany
    {
        return $this->belongsToMany(Course::class);
    }

}
