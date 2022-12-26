<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Billing extends Model
{
    use HasFactory;

    const AWAITING = 'awaiting';
    const PAID = 'paid';

    protected $fillable = [
        'debt_id',
        'bar_code',
        'our_number',
        'due_date',
        'status',
        'paid_amount',
        'paid_at',
        'paid_by'
    ];
}
