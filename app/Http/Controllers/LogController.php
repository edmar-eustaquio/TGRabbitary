<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Log;

class LogController
{
    public function index() {
        return inertia('Log/Index');
    }

    public function get(Request $req){
        return response(
            Log::with(['user:id,name'])
                ->where(function($q) use($req){
                    $s = $req['search'];
                    if($s)
                        $q->where(function($q) use($s){
                            $q->where('action', 'LIKE', $s . '%')
                                ->orWhere('section', 'LIKE', $s . '%')
                                ->orWhereHas('user', function($q) use($s){
                                    $q->where('name', 'LIKE', $s . '%');
                                });
                        });

                    $q->whereDate('date', '>=', $this->getDate($req['from']))
                        ->whereDate('date', '<=', $this->getDate($req['until']));
                })
                ->paginate($req['page_size'] ?? 10)
        );
    }

    private function getDate($date){
        if (!$date) return substr(now(), 0, 10);

        return date('Y-m-d', strtotime(substr($date, 0, 10) . ' +1 day'));
    }
}
