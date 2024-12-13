<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseOrder extends Model
{
    use HasFactory;
    protected $table = 'purchase_orders';
    protected $primaryKey = 'iPurchOrderPk';
    protected $fillable = [
        'iPurchOrderComfk',
        'iPurchOrderCustDfk',
        'iPurchOrderNo',
        'dPurchOrderdate',
        'yPurchOrderSubtotal',
        'yPurchOrderTotalPayment',
        'iPurchOrderDiscount',
        'iPurchOrderShipping',
        'iPurchOrderTax',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class, 'iPurchOrderComfk', 'id'); // Adjust foreign and local keys if needed
    }

    public function customer()
    {
        return $this->belongsTo(CustomerDetail::class, 'iPurchOrderCustDfk', 'iCustDPk'); // Adjust foreign and local keys if needed
    }
    public function companyMaintenance()
    {
        return $this->belongsTo(CompanyMaintenance::class);

    }
}
