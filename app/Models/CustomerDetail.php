<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerDetail extends Model
{
    use HasFactory;

    protected $primaryKey = 'iCustDPk';
    protected $table = 'customer_details';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = true;
    protected $fillable = [
        'cCustDName',  //customer name
        'cCustDPhoneNo', //customer phone no
        'cCustDAddress', // customer company address
        'cCustDCity', // customer company city
        'cCustDState',//customer compnay state
        'cCustDPostcode',//customer company postcode
        'iCustDCompfk',//foreign key take the company id 
        'cCustDCompName',//customer company name
        'cCustDCompNo',//customer company no
        'cCustDCompOfficeNo',//customer company office no
        'cCustDCompEmail',//csutomer company email
        'cCustDCompWebsite',//customer company website
    ];

    public function company()
    {
        return $this->belongsTo(Company::class, 'iCustDCompfk', 'id');
    }
    public function invoices()
    {
        return $this->hasMany(Invoice::class, 'iInvcCustDfk', 'iCustDPk');
    }
    public function receipt()
    {
        return $this->hasMany(Receipt::class, 'iRecptCustDfk', 'iCustDPk');
    }
    public function purchaseOrder()
    {
        return $this->hasMany(PurchaseOrder::class, 'iPurchOrderCustDfk', 'iCustDPk');
    }
    public function quotation()
    {
        return $this->hasMany(Quotation::class, 'iQuoCustDfk', 'iCustDPk');
    }
}
