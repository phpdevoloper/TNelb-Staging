<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TnelbLicense extends Model
{
    use HasFactory;

    protected $table = 'tnelb_license';
    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = false;

    protected $fillable = [
        'application_id',
        'license_number',
        'issued_by',
        'issued_at',
        'expiry_date',
        'expires_at',
        'license_status',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'issued_at' => 'date',
        'expiry_date' => 'date',
        'expires_at' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'date',
    ];

    public function isExpired()
    {
        return $this->expires_at && $this->expires_at < now();
    }

    public function daysRemaining()
    {
        return $this->expires_at ? now()->diffInDays($this->expires_at, false) : null;
    }
}
