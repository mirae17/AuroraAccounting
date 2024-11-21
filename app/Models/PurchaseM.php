<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseM extends Model
{
    use HasFactory;
    protected $table = 'purchase_master';
    protected $primaryKey = 'ipmaspk';

    protected $fillable = [

        'dpmasdate',
        'ipmasSuppfk',
        'cpmascodeprod',
        'ypmaspayment',
        'ypmasdeposit',
        'ipmasPymtdfk',
        'cara_jualan',
        'ipmasinvoiceref',
        'cpmasnotes',
    ];

    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class, 'ipmasPymtdfk', 'iPymtdPk');
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'ipmasSuppfk', 'iSuppPk');
    }
    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }
}
