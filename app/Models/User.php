<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $primaryKey = 'id'; // Assuming 'id' is the primary key field
    public $incrementing = false; // Set to false to indicate that the IDs are not auto-incrementing
    protected $keyType = 'string'; // Specify the data type of the primary key


    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
        'name',
        'email',
        'password',
        'referer_code',
        'phone_number',
        'user_type',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public $selectableFields = [
        'users.id',
        'users.email',
        'users.referer_code',
       
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($user) {
            $user->id = (string) Str::uuid(); // Generate a new UUID for the id field
        });
    }
}

?>