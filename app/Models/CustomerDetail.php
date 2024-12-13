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
        'cCustDName',
        'cCustDPhoneNo',
        'cCustDAddress',
        'cCustDCity',
        'cCustDState',
        'cCustDPostcode',
        'iCustDCompfk',
        'cCustDCompName',
        'cCustDCompNo',
        'cCustDCompOfficeNo',
        'cCustDCompEmail',
        'cCustDCompWebsite',
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
}
