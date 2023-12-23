<?php

namespace App\Models;

use App\Models\Vendor;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PurchaseOrder extends Model
{
    use HasFactory;

    // public $guarded = ['code'];
    public $primaryKey = 'code';
    protected $keyType = "string";
    public $timestamps = false;

    protected $fillable = [
        'code',
        'vendor_code',
        'description',
        'date',
        'qty', 
        'total_price',
        'discount',
        'charge',
        'nett',
    ];

    // public function products(): BelongsTo
    // {
    //     return $this->belongsTo(Product::class, 'product_id', 'id');
    // } 
    public function vendor(): BelongsTo
    {
        return $this->belongsTo(Vendor::class, 'vendor_code', 'code');
    } 
}
