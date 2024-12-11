<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    protected $primaryKey = 'id';
    protected $table = 'companies';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = true;

    protected $fillable = [
        'code',
        'description'
    ];


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
    public function employees()
    {
        return $this->hasMany(Employee::class, 'company_id');
    }
    public function customerDetail()
    {
        return $this->hasMany(CustomerDetail::class, 'iCustDCompfk', 'id');
    }

    public function inventories()
    {
        return $this->hasMany(Inventory::class, 'iInvComfk');
    }

    public function product()
    {
        return $this->hasMany(Product::class, 'iProComfk');
    }

    public function companyMaintenance()
    {
        return $this->hasMany(CompanyMaintenance::class, 'iCompMainName', 'id');
    }


}
