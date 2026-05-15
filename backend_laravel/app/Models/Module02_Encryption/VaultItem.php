<?php

namespace App\Models\Module02_Encryption;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VaultItem extends Model
{
    use HasFactory;

    protected $table = 'vault_items';

    protected $fillable = [
        'name', 'type', 'value', 'metadata', 'user_id', 'access_count', 'last_accessed_at'
    ];

    protected $casts = [
        'metadata' => 'array',
        'last_accessed_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(Module01_IAM\User::class);
    }
}