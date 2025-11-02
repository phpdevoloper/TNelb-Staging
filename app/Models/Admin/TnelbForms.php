<?php

namespace App\Models\admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TnelbForms extends Model
{
    use HasFactory;

    public $timestamps = true;
    // protected $casts = [
        //     'freshamount_starts' => 'datetime',
        //     'renewalamount_starts' => 'datetime',
        //     'latefee_starts' => 'datetime',
        // ];
        
    protected $table="tnelb_forms";
    protected $primaryKey = 'id'; 
        
    protected $fillable = [
        'form_name',
        'licence_id',
        'cert_licence_code',
        'form_code',
        // 'license_name',
        'fresh_fee_amount',
        'fresh_fee_starts',
        'fresh_fee_ends',
        'renewal_amount',
        'renewalamount_starts',
        'renewalamount_ends',
        'latefee_amount',
        'latefee_starts',
        'latefee_ends',
        'duration_freshfee',
        'duration_freshfee_starts',
        'duration_freshfee_ends',
        'duration_renewalfee',
        'duration_renewalfee_starts',
        'duration_renewalfee_ends',
        'duration_latefee',
        'duration_latefee_starts',
        'duration_latefee_ends',
        'instructions_upload',
        'category',
        'status',
        'created_by',
        'updated_by',
        'created_at',
        'updated_at'
    ];

}
