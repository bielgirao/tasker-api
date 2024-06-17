<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{

    protected $fillable = [
        'name',
        'status',
        'deadline',
        'description'
    ];


    // Mutator to change dates to laravel format
    public function setDeadlineAttribute($value): void
    {
        $this->attributes['deadline'] = Carbon::parse($value)->format('Y-m-d H:i:s');
    }

}

