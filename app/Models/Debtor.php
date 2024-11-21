<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;


class Debtor extends Model
{
    use HasFactory;

    protected $table = 'debtor';
    protected $primaryKey = 'iDebtorPk';

    protected $fillable = [
        'cDebtorCode',
        'cDebtorDesc',
        'company_id',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($sale) {
            if (Auth::check()) {
                $sale->company_id = Auth::user()->company_id;
            }
        });
    }
}
