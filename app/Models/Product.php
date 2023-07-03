<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'name',
        'ingridients',
        'weight',
        'price',
        'date',
    ];

    protected $guarded = [];

    protected $hidden = [];

    protected $casts = [];
}
