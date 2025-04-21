<?php
namespace App\Services;

use App\Models\Log;

class CLog {
    public static function create($action, $section){
        Log::create([
            'user_id' => auth()->user()->id,
            'action' => $action,
            'section' => $section,
            'date' => date('Y-m-d h:i:s')
        ]);
    }
}
?>