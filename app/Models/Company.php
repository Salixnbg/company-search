<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $fillable = [
        'enterprise_number',
        'vat_number',
        'name',
        'status',
        'legal_form',
        'street',
        'postal_code',
        'city',
        'start_date',
    ];
}