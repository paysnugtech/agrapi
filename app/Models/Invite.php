<?php
// app/Models/Invite.php
namespace App\Models;
use Illuminate\Support\Str;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; 

class Invite extends Model
{
    use HasFactory, SoftDeletes;

    protected $primaryKey = 'id'; // Assuming 'id' is the primary key field
    public $incrementing = false; // Set to false to indicate that the IDs are not auto-incrementing
    protected $keyType = 'string'; // Specify the data type of the primary key

    protected $table = 'invite';
    protected $guarded = [];


    /**
     * Select an invite by invite_id or email.
     *
     * @param string $identifier
     * @return \App\Models\Invite|null
     */
    public static function findByIdentifier($identifier)
    {
        return self::where('invite_id', $identifier)
            ->orWhere('email', $identifier)
            ->first();
    }
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($invite) {
            $invite->id = (string) Str::uuid();
        });
    }
}
?>