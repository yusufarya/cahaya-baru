<?php

namespace App\Models;

use App\Models\Customer;
use App\Models\SalesOrderDetail;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SalesOrder extends Model
{
    use HasFactory;
    public $guarded = ['id'];
    public $timestamps = false;

    /**
     * Get the customers that owns the SalesOrder
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function customers(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'customer_code', 'code');
    }

    public function salesOrderDetails(): BelongsTo
    {
        return $this->belongsTo(SalesOrderDetail::class, 'id', 'sales_order_id');
    }
}
