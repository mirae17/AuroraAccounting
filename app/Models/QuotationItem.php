<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuotationItem extends Model
{

    use HasFactory;

    protected $table = 'quotation_items';
    protected $primaryKey = 'iQuoItemPk';

    protected $fillable = [
        'iQuoItemQuofk',
        'cQuoItemProductCode',
        'cQuoItemDescription',
        'yQuoItemPriceUnit',
        'iQuoItemQuantity',
        'yQuoItemTotal',
    ];

    // Relationship with Quotation
    public function quotation()
    {
        return $this->belongsTo(Quotation::class, 'iQuoItemQuofk', 'iQuoPk');
    }

}
