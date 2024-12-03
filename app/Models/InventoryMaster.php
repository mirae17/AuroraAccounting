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

        'cInvmasInvCodefk',
        'cInvmasInvNamefk',
        'dInvmasDate',
        'cInvmasSuppfk',
        'iInvmasQuanIn',
        'iInvmasQuanOut',
        'yInvmasDeposit',
        'yInvmasPayment',
        'cInvmasPymtdfk',
        'cInvmasInvoice',
        'cInvmasEmpfk',
        'cInvmasCreditorfk',
        'cInvmasCompfk',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class, 'cInvmasCompfk');
    }

    public function paymentmethods()
    {
        return $this->belongsTo(PaymentMethod::class, 'cInvmasPymtdfk');
    }
    public function inventory()
    {
        return $this->belongsTo(Inventory::class);
    }
    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'cInvmasSuppfk');
    }
    public function employee()
    {
        return $this->belongsTo(Employee::class, 'cInvmasEmpfk');
    }
}
