<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceItem extends Model
{
    use HasFactory;
    protected $table = 'invoice_items';
    protected $primaryKey = 'iInvcItemPk';


    protected $fillable = [
        'iInvcItemInvcfk',
        'cInvcItemProductCode',
        'cInvcItemDescription',
        'yInvcItemPriceUnit',
        'iInvcItemQuantity',
        'yInvcItemTotal',
    ];

    // Relationship with Quotation
    public function invoice()
    {
        return $this->belongsTo(Invoice::class, 'iInvcItemInvcfk', 'iInvcPk');
    }
}
