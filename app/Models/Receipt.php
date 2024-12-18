<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Receipt extends Model
{
    use HasFactory;
    protected $table = 'receipts';
    protected $primaryKey = 'iRecptPk';

    protected $fillable = [
        'iRecptComfk',
        'iRecptCustDfk',
        'iRecptNo',
        'dRecptdate',
        'yRecptSubtotal',
        'yRecptTotalPayment',
        'iRecptDiscount',
        'iRecptShipping',
        'iRecptTax',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class, 'iRecptComfk', 'id'); // Adjust foreign and local keys if needed
    }

    public function customer()
    {
        return $this->belongsTo(CustomerDetail::class, 'iRecptCustDfk', 'iCustDPk'); // Adjust foreign and local keys if needed
    }
    public function companyMaintenance()
    {
        return $this->belongsTo(CompanyMaintenance::class);
    }
    public function items()
    {
        return $this->hasMany(ReceiptItem::class, 'iRecptItemRecptfk', 'iRecptPk');
    }
}
