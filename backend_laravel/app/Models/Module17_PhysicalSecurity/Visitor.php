<?php

namespace App\Models\Module17_PhysicalSecurity;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Visitor extends Model
{
    use HasFactory;

    protected $table = 'visitors';

    protected $fillable = [
        'full_name', 'identification_number', 'phone', 'email',
        'company', 'host_employee_id', 'purpose', 'check_in_at',
        'check_out_at', 'badge_number', 'status'
    ];

    protected $casts = [
        'check_in_at' => 'datetime',
        'check_out_at' => 'datetime',
    ];

    public function host()
    {
        return $this->belongsTo(Module01_IAM\User::class, 'host_employee_id');
    }
}