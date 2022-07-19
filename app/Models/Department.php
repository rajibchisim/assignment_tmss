<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;

    public function batches()
    {
        return $this->hasMany(Batch::class);
    }


    public function students()
    {
        return $this->hasManyThrough(Student::class, Batch::class);
    }

}
