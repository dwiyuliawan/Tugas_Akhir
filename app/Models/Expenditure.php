<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Expenditure extends Model
{
    protected $table = 'expenditures';
    protected $primaryKey = 'expenditure_id';
    protected $guarded = [];
}
