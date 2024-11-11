<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    use HasFactory;

    // Define the table name
    protected $table = 'payments';

    // Specify the primary key column
    protected $primaryKey = 'iPymtdPk';

    // Specify that the primary key is not an auto-incrementing integer if necessary
    public $incrementing = true;

    // Set the key type to integer (or string if needed)
    protected $keyType = 'int';

    // Ensure timestamps are enabled if using `created_at` and `updated_at`
    public $timestamps = true;

    // Define fillable fields
    protected $fillable = [
        'cPymtdCode',
        'cPymtdDesc',
    ];
}
