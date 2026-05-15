<?php

namespace App\Models\Module28_SystemAdmin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LanguageSetting extends Model
{
    use HasFactory;

    protected $table = 'language_settings';

    protected $fillable = [
        'locale', 'name', 'direction', 'is_default',
        'is_active', 'translations_available'
    ];

    protected $casts = [
        'is_default' => 'boolean',
        'is_active' => 'boolean',
        'translations_available' => 'boolean',
    ];
}