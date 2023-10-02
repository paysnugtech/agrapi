<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class CommissionProcess extends Model
{
    use HasFactory;

    protected $primaryKey = 'id'; // Assuming 'id' is the primary key field
    public $incrementing = false; // Set to false to indicate that the IDs are not auto-incrementing
    protected $keyType = 'string'; // Specify the data type of the primary key

    protected $table = 'commission_process';
    protected $fillable = ['id','organization_name', 'short_code', 'organization_id', 'amount', 'status'];
    protected $casts = [
        'status' => 'boolean',
    ];

    public $selectableFields = [
        'commission_process.id',
        'commission_process.organization_id',
       
    ];

   

   

    // Define relationships with other models (if any)
    public function commissionUsers()
    {
        return $this->hasMany(CommissionUser::class, 'organization_id', 'organization_id');
    }

  
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($user) {
            $user->id = (string) Str::uuid(); // Generate a new UUID for the id field
        });
    }
}
