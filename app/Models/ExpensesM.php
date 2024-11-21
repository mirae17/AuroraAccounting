<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExpensesM extends Model
{
    use HasFactory;
    protected $table = 'expenses_master';
    protected $primaryKey = 'iexmaspk';

    protected $fillable = [
        'dexmasdate',
        'cexmasExpfk',
        'yexmaspayment',
        'iexmasPymtdfk',
        'iexmasinvoiceref',
        'cexmasnotes',
        'company_id',
    ];

    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class, 'iexmasPymtdfk', 'iPymtdPk');
    }

    public function expenses()
    {
        return $this->belongsTo(Expense::class, 'cexmasExpfk', 'iExpPk');
    }
    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }
}
