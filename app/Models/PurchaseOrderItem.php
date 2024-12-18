<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseOrderItem extends Model
{
    use HasFactory;
    protected $table = 'purchase_order_items';
    protected $primaryKey = 'iPurchOrderItemPk';

    protected $fillable = [
        'iPurchOrderItemPurchOrderfk',
        'cPurchOrderItemProductCode',
        'cPurchOrderItemDescription',
        'yPurchOrderItemPriceUnit',
        'iPurchOrderItemQuantity',
        'yPurchOrderItemTotal',
    ];


    public function purchaseOrder()
    {
        return $this->belongsTo(PurchaseOrder::class, 'iPurchOrderItemPurchOrderfk', 'iPurchOrderPk');
    }
}
