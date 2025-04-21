<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'active'
    ];

    public function budgets(){
        return $this->hasMany(Budget::class, 'category_id')->where('is_budget', 1);
    }
    public function expenses(){
        return $this->hasMany(Budget::class, 'category_id')->where('is_budget', 0);
    }
}
