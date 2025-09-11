<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlaceholdImage extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'params',
    ];
}
