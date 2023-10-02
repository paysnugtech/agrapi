<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Wallet extends Model
{

    protected $primaryKey = 'id'; // Assuming 'id' is the primary key field
    public $incrementing = false; // Set to false to indicate that the IDs are not auto-incrementing
    protected $keyType = 'string'; // Specify the data type of the primary key
    protected $fillable = [
        'id',
        'user_id',
        'balance',
    ];
    protected $table = 'wallets';

    // Add any other custom methods or relationships, if needed
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($user) {
            $user->id = (string) Str::uuid(); // Generate a new UUID for the id field
        });
    }
}
