<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Admin\MstRoles;

class Mst_Staffs extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'mst_staffs'; 

    protected $primaryKey = 's_id';

    protected $fillable = ['staff_id','role_id','staff_name', 'staff_email', 'login_passwd','status', 'updated_by'];

    // protected $hidden = [''];

    public static function findByEmail($value)
    {
        return self::where('staff_email', $value)
            ->first();
    }

    public function role() {
        return $this->belongsTo(MstRoles::class, 'role_id');
    }
}
