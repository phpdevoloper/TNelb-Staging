<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StaffAssigned extends Model
{
    protected $table = 'staff_assigned';

    protected $primaryKey = 'id';

    // Since created_at & updated_at are DATE (not timestamps)
    public $timestamps = false;

    protected $fillable = [
        'staff_id',
        'form_id',
        'is_active',
        'assigned_at',
        'created_by',
        'updated_by',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'assigned_at' => 'date',
        'created_at'  => 'date',
        'updated_at'  => 'date',
    ];

    /*
     |--------------------------------------------------------------------------
     | Optional Relationships
     |--------------------------------------------------------------------------
     */

    // Link to staff master
    // public function staff()
    // {
    //     return $this->belongsTo(Staff::class, 'staff_id');
    // }

    // // Link to forms master
    // public function form()
    // {
    //     return $this->belongsTo(Form::class, 'form_id');
    // }
}