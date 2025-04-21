<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Weight extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'rabbit_id',
        'weight',
        'date',
    ];
    
    public function rabbit(){
        return $this->belongsTo(Rabbit::class, 'rabbit_id');
    }
}
