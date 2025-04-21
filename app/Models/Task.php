<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'title',
        'desc',
        'priority',
        'due_date',
        'completed_at',
        'rabbit_id',
    ];

    public function rabbit(){
        return $this->belongsTo(Rabbit::class, 'rabbit_id');
    }
}
