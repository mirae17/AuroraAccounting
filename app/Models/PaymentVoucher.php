<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentVoucher extends Model
{
    use HasFactory;

    protected $table = 'payment_vouchers';

    protected $primaryKey = 'iPymtVchrPk';

    public $timestamps = true;

    protected $fillable = [
        'iPymtVchrPymtdfk',
        'cPymtVchrNo',
        'cPymtVchrDesc',
        'dPymtVchrDate',
        'cPymtVchrNoAcc',
        'cPymtVchrMethod',
        'cPymtVchrName',
        'yPymtVchrTotal',
        'cPymtVchrRefNo',
        'iPymtVchrCompfk',
    ];

    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class, 'iPymtVchrPymtdfk', 'iPymtdPk');
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
