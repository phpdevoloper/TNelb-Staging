<?php

namespace App\Models\Admin;

use App\Models\MstLicence;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeesValidity extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'fees_validity';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'licence_id',
        'form_type',
        'duration_freshfee',
        'duration_freshfee_starts',
        'duration_freshfee_ends',
        'duration_renewalfee',
        'duration_renewalfee_starts',
        'duration_renewalfee_ends',
        'duration_latefee',
        'duration_latefee_starts',
        'duration_latefee_ends',
        'status',
        'created_by',
        'updated_by',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'duration_freshfee_starts' => 'date',
        'duration_freshfee_ends'   => 'date',
        'duration_renewalfee_starts' => 'date',
        'duration_renewalfee_ends'   => 'date',
        'duration_latefee_starts'    => 'date',
        'duration_latefee_ends'      => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Optional relationship with Licence model
     * (assuming mst_licences table)
     */
    public function licence()
    {
        return $this->belongsTo(MstLicence::class, 'licence_id', 'id');
    }
}