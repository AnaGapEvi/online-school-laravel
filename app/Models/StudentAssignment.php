<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentAssignment extends Model
{
    use HasFactory;
    protected $fillable =[
        'assignment_id',
        'file_name',
        'user_id',
        'verified'
    ];

    public function users(){
        return $this->belongsToMany(User::class);
    }

    public function assignment(){
        return $this->belongsTo(Assignment::class);
    }
}
