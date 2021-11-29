<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class License extends Model
{
    use HasFactory;

    public static function getAllLicenses(/*$page*/)
        {
            // return DB::table('licenses')
            //         ->select('id', 'name', 'email', 'role', 'from', 'to')
            //         ->where('is_admin', '!=', '1')
            //         ->where('is_revoke', '==', '0')
            //         ->orderBy('name', 'desc')
            //         ->paginate(10, ['*'], 'page', $page);
            return DB::table('licenses')
                    ->select('id', 'name', 'email', 'role', 'from', 'to', 'machine_key','license_key')
                    ->orderBy('name', 'desc')
                    ->get();
        }
    
    
}
