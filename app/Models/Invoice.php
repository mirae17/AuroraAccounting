<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;
    protected $table = 'invoices';
    protected $primaryKey = 'iInvcPk';

    protected $fillable = [
        'iInvcComfk',
        'iInvcCustDfk',
        'iInvcNo',
        'dInvcdate',
        'yInvcSubtotal',
        'yInvcTotalPayment',
        'iInvcDiscount',
        'iInvcShipping',
        'iInvcTax',
    ];


    public function company()
    {
        return $this->belongsTo(Company::class, 'iInvcComfk', 'id'); // Adjust foreign and local keys if needed
    }

    public function customer()
    {
        return $this->belongsTo(CustomerDetail::class, 'iInvcCustDfk', 'iCustDPk'); // Adjust foreign and local keys if needed
    }
    public function companyMaintenance()
    {
        return $this->belongsTo(CompanyMaintenance::class);
    }


}
