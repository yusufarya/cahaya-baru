<?php

namespace App\Models;

use App\Models\Size;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RequestOrder extends Model
{
    use HasFactory;
    protected $table = 'request_orders';
    public $guarded = ['id'];
    public $primaryKey = 'code';
    public $keyType = 'string';
    public $timestamps = false;

    /**
     * Get the customers that owns the RequestOrder
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function customers(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'customer_code', 'code');
    }

    public function sizes(): BelongsTo
    {
        return $this->belongsTo(Size::class, 'size_id', 'id');
    }
}
