<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Employee extends Model
{
    use HasFactory;
   
    protected $table = 'employees';
    protected $primaryKey = 'iEmpmasPk';

    protected $fillable = [
        'cEmpNo',
        'cEmpName',
        'company_id',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function sales()
    {
        return $this->hasMany(Sales::class, 'ismasusersfk');
    }

    
}
