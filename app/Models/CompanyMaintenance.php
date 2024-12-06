<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyMaintenance extends Model
{
    use HasFactory;
    protected $table = 'company_maintenances';

    // The primary key associated with the table.
    protected $primaryKey = 'iCompMainPk';

    // Indicates if the model should be timestamped.
    public $timestamps = true;

    // The attributes that are mass assignable.
    protected $fillable = [
        'iCompMainName',
        'iCompMainRegNo',
        'iCompMainAddress',
        'iCompMainPhoneNo',
        'iCompMainEmail',
        'iCompMainLogo'
    ];
    protected $hidden = [
        // If you want to hide any specific attribute like password or sensitive data
    ];

    // The attributes that should be cast to native types (Optional, depends on your fields)
    protected $casts = [
        // 'field_name' => 'datatype', example: 'created_at' => 'datetime'
    ];

    public function company()
    {
        return $this->belongsTo(Company::class, 'iCompMainName');
    }

}
