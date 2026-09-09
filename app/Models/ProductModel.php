<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductModel extends Model
{
     // Explicitly define table name to avoid 'models' error
     protected $table = 'product_models';

     protected $fillable = [
         'product_id',
         'model_name',
         'qty',
         'purchase_price',
     ];

     protected $casts = [
         'qty' => 'integer',
         'purchase_price' => 'decimal:2',
     ];

     public function getTotalValueAttribute()
     {
         return $this->qty * $this->purchase_price;
     }

     public function purchases()
     {
         return $this->hasMany(Purchase::class, 'model_id');
     }
 
     public function product()
     {
         return $this->belongsTo(Product::class);
     }
}
