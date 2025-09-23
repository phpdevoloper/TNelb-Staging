<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class FormaModel extends Model
{
    use HasFactory;

    protected $table = 'tnelb_ea_applications';

    public static function getPendingCountForma()
    {
        return self::whereIn('application_status', ['P','RE'])
            ->count();
    }
    public static function getcompleteCountForma()
    {
       return self::whereIn('application_status', ['F', 'A','RF'])
            ->whereIn('processed_by', ['A', 'SE', 'PR', 'S'])
            ->count();
    }

      /**
     * Get pending and completed counts for auditors.
     */
    public static function getAuditorFormAPendingCounts()
    {
        return DB::table('tnelb_ea_applications as ta')
        ->select(
            DB::raw("COUNT(CASE WHEN ta.application_status = 'F' AND ta.processed_by = 'S' THEN 1 END) as pending_count"),
            DB::raw("COUNT(CASE WHEN ta.application_status IN ('F','RF','A') AND ta.processed_by IN ('A', 'PR', 'SE') THEN 1 END) as completed_count")
        )
        ->first();
    }

    public static function getSecFormACounts()
    {
        return DB::table('tnelb_ea_applications as ta')
        ->select(
            DB::raw("COUNT(CASE WHEN ta.application_status IN ('F','RF') AND ta.processed_by IN ('A','S','SPRE') THEN 1 END) as pending_count"),
            DB::raw("COUNT(CASE WHEN ta.application_status IN ('F','RF','A') AND ta.processed_by IN ('PR', 'SE','SPRE') THEN 1 END) as completed_count")
        )
        ->first();
    }
}
