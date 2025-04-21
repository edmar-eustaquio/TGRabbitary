<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rabbit extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'name',
        'images',

        'gender',
        'birth',
        'note',
        'sold',
        'status',
        'illness',
    ];
    
    public function product(){
        return $this->belongsTo(Product::class, 'product_id');
    }
    public function illness(){
        return $this->hasOne(Illness::class, 'rabbit_id')->where('status', 'Under Treatment')->orderByDesc('date');
    }
    public function latest_weight(){
        return $this->hasOne(Weight::class, 'rabbit_id')->orderByDesc('date');
    }
    
}
