<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SaleDetail extends Model
{
    protected $table = 'sale_details';
    protected $primaryKey = 'sale_detail_id';
    protected $guarded = [];

    public function products()
    {
        return $this->hasOne(Product::class, 'product_id', 'product_id');
    }

}
