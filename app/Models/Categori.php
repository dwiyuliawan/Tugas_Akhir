<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Categori extends Model
{
    protected $table = 'categoris';
    protected $primaryKey = 'categori_id';
    protected $guarded = [];

    protected $fillable = ['categori_name'];
}
