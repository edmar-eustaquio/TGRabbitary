<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Illness extends Model
{
    use HasFactory;

    protected $fillable = [
        'rabbit_id',
        'illness',
        'date',
        'status',
    ];
    
    public function rabbit(){
        return $this->belongsTo(Rabbit::class, 'rabbit_id');
    }
}
