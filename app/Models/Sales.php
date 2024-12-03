<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Sales extends Model
{
    use HasFactory;

    protected $primaryKey = 'ismaspk';
    protected $table = 'sales_master';
    protected $fillable = [
        'dsmasdate', 
        'csmasdesc', 
        'ysmasdeposit', 
        'ismasPymtdfk', 
        'csmasDebtorfk', 
        'ismasinvoiceref', 
        'cara_jualan',
        'ysmaspayment', 
        'ismasusersfk',
        'csmasDebtorfk',
        'company_id',
    ];

    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class, 'ismasPymtdfk', 'iPymtdPk');
    }

    public function debtor()
    {
        return $this->belongsTo(Debtor::class, 'csmasDebtorfk', 'iDebtorPk');
    }
    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function employee()
{
    return $this->belongsTo(Employee::class, 'ismasusersfk','iEmpmasPk');
}

}
