<?php
namespace App\Models;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class CommissionUser extends Model
{
    use HasFactory;

    protected $primaryKey = 'id'; // Assuming 'id' is the primary key field
    public $incrementing = false; // Set to false to indicate that the IDs are not auto-incrementing
    protected $keyType = 'string'; // Specify the data type of the primary key


    protected $fillable = ['user_id', 'organization_name', 'short_code', 'organization_id', 'percentage', 'status'];
    protected $casts = [
        'status' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($user) {
            $user->id = (string) Str::uuid(); // Generate a new UUID for the id field
        });
    }
}
