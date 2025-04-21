<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderDetails extends Model
{
    use HasFactory;
    
    public $timestamps = false;

    protected $fillable = [
        'order_id',
        'product_id',
        'qty',
    ];
    
    public function product(){
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function order(){
        return $this->belongsTo(Order::class, 'order_id');
    }

    public function rate(){
        return $this->hasOne(Rating::class, 'order_details_id');
    }

}
