<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable= [
        'user_id',
        'action',
        'section',
        'date'
    ];

    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }
    
}
