<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Breeding extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'buck_id',
        'doe_id',
        'tested',
        'bred',
        'breed_duration',
        'out_count',
        'kindled',
        'weaning',
        'alive',
        'dead',
        'expected_kindle',
    ];

    public function buck(){
        return $this->belongsTo(Rabbit::class, 'buck_id');
    }
    public function doe(){
        return $this->belongsTo(Rabbit::class, 'doe_id');
    }
}
