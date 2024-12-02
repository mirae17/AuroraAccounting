<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    protected $table = 'companies';
    protected $fillable = ['code', 'description'];

    public function users()
    {
        return $this->hasMany(User::class);
    }
    public function payments()
    {
        return $this->hasMany(PaymentMethod::class, 'company_id', 'id');
    }

    public function sales()
    {
        return $this->hasMany(Sales::class);
    }

    public function expenses()
    {
        return $this->hasMany(Expense::class, 'company_id', 'id');
    }

    public function expensesM()
    {
        return $this->hasMany(ExpensesM::class, 'company_id', 'id');
    }
    public function purchaseM()
    {
        return $this->hasMany(PurchaseM::class);
    }

    public function suppliers()
    {
        return $this->hasMany(Supplier::class);
    }

    public function debtors()
    {
        return $this->hasMany(Debtor::class, 'company_id', 'id');
    }


}
