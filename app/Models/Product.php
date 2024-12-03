<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $table = 'products';
    protected $primaryKey = 'iProPk';

    protected $fillable = [
        'cProName',
        'cProCode',
        'cProType',
        'iProUom',
        'yProPrice',
        'iProComfk',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class, 'iProComfk');
    }
}
