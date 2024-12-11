<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryMaster extends Model
{
    use HasFactory;
    protected $table = 'inventory_masters';
    protected $primaryKey = 'iInvmasPk';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = true;

    protected $fillable = [

        'cInvmasType',
        'cInvmasInvCodefk',
        'dInvmasDate',
        'cInvmasSuppfk',
        'iInvmasQuanIn',
        'iInvmasQuanOut',
        'yInvmasDeposit',
        'yInvmasPayment',
        'cInvmasPymtdfk',
        'cInvmasInvoice',
        'cInvmasEmpfk',
        'iInvmasInvPricefk',
        'cInvmasCreditorfk',
        'yInvmasPayment',
        'cInvmasCompfk',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class, 'cInvmasCompfk');
    }

    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class, 'cInvmasPymtdfk');
    }
    public function inventories()
    {
        return $this->belongsTo(Inventory::class, 'cInvmasInvCodefk');
    }
    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'cInvmasSuppfk');
    }
    public function employees()
    {
        return $this->belongsTo(Employee::class, 'cInvmasEmpfk', 'iEmpmasPk');
    }
}
