<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReceiptItem extends Model
{
    use HasFactory;

    protected $table = 'receipt_items';
    protected $primaryKey = 'iRecptItemPk';

    protected $fillable = [
        'iRecptItemRecptfk',
        'cRecptItemProductCode',
        'cRecptItemDescription',
        'yRecptItemPriceUnit',
        'iRecptItemQuantity',
        'yRecptItemTotal',
    ];

    // Relationship with Quotation
    public function receipt()
    {
        return $this->belongsTo(Receipt::class, 'iRecptItemRecptfk', 'iRecptPk');
    }
}
