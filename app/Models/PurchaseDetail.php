<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseDetail extends Model
{
    protected $table = 'purchase_details';
    protected $primaryKey = 'purchase_detail_id';
    protected $guarded = [];

    public function products()
    {
        return $this->hasOne(Product::class, 'product_id', 'product_id');
    }
}
