<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quotation extends Model
{
    use HasFactory;

    protected $table = 'quotations';
    protected $primaryKey = 'iQuoPk';
    protected $fillable = [
        'iQuoNo',
        'dQuodate',
        'iQuoComfk',
        'iQuoCustDfk',
        'yQuoSubtotal',
        'iQuoDiscount',
        'iQuoTax',
        'iQuoShipping',
        'yQuoTotalPayment',

    ];


    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function customer()
    {
        return $this->belongsTo(CustomerDetail::class, 'iQuoCustDfk', 'iCustDPk');
    }

    public function items()
    {
        return $this->hasMany(QuotationItem::class, 'iQuoItemQuofk', 'iQuoPk');
    }

    public function companyMaintenance()
    {
        return $this->hasMany(CompanyMaintenance::class);
    }

}
