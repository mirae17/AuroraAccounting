<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesMaster extends Model
{
    use HasFactory;

    protected $primaryKey = 'ismaspk';
    protected $table = 'sales_master';
    protected $fillable = [
        'dsmasdate', 'csmasdesc', 'ysmasdeposit', 'ismasPymtdfk', 
        'ismasSuppfk', 'ismasinvoiceref', 'ysmaspayment', 'ismasusersfk'
    ];

    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class, 'ismasPymtdfk', 'iPymtdPk');
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'ismasSuppfk', 'iSuppPk');
    }
}
