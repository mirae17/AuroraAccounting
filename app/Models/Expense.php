<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Expense extends Model
{
    use HasFactory;
    protected $table = 'expenses';
    protected $primaryKey = 'iExpPk';

    protected $fillable = [
        'cExpCode',
        'cExpDesc',
        'company_id',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    
   


}
