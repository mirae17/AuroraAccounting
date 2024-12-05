<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{

    use HasFactory;
    protected $table = 'inventories';
    protected $primaryKey = 'iInvPK';

    protected $fillable = [
        'cInvName',
        'cInvCode',
        'cInvType',
        'iInvUom',
        'yInvPrice',
        'iInvComfk',
    ];


    public function company()
    {
        return $this->belongsTo(Company::class, 'iInvComfk');
    }
}
