<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Installment extends Model
{

    // In your Installment model
    protected $dates = ['due_date'];  // This will automatically cast due_date to Carbon

    
    protected $fillable = [
        'customer_id',
        'product_id',
        'purchase_id',
        'amount',
        'paid_amount',
        'status',
        'due_date',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'due_date' => 'datetime',
    ];
    
    public function purchase() {
        return $this->belongsTo(Purchase::class);
    }
    
    public function payments() {
        return $this->hasMany(InstallmentPayment::class);
    }

    
}
