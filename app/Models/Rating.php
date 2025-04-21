<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    use HasFactory;

    
    protected $fillable = [
        'order_details_id',
        'user_id',
        'rate',
        'comment',
        'images'
    ];
    
    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }
    public function order_details(){
        return $this->belongsTo(OrderDetails::class, 'order_details_id');
    }
}
