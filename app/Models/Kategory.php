<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Kategory extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'user_id',
        'category_name',
    ];

    public $table = 'kategories';
}
