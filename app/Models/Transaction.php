<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Transaction extends Model
{
    protected $primaryKey = 'id'; // Assuming 'id' is the primary key field
    public $incrementing = false; // Set to false to indicate that the IDs are not auto-incrementing
    protected $keyType = 'string'; // Specify the data type of the primary key
    protected $fillable = [
        'id',
        'user_id',
        'amount',
        'transaction_type', 
        'short_code',
        'description',
        'organization_id',
        'transaction_name',
    ];

    // Your relationships or other custom methods can be defined here

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($transaction) {
            $transaction->id = (string) Str::uuid(); // Generate a new UUID for the id field
        });
    }
}
