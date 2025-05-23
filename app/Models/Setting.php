<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable= [
        'invoice_no',
        'deal_desc',
        'number',
        'deal_date_until',
        'quote_image',
        'quote_title',
        'quote_body',
    ];
}
