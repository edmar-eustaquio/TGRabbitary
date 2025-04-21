<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Illness;
use App\Http\Requests\IllnessRequest;
use CLog;

class IllnessController
{
    public function index(){
        return inertia('Rabbit/Illness');
    }

    public function get(Request $req){
        return response(
            Illness::with(['rabbit'])
                ->where(function($q) use($req){
                    if ($req['search'])
                        $q->where('illness', $req['search']);
                        // $q->where('illness', 'LIKE', $req['search'].'%');
                    if ($req['rabbit_id'])
                        $q->where('rabbit_id', $req['rabbit_id']);
                })
                ->orderBy('date')
                ->paginate($req['page_size'] ?? 10)
        );
    }
    public function dropdown(){
        return response(
            array_column(Illness::select(['illness'])->orderBy('illness')->groupBy('illness')->get()->toArray(), 'illness')
        );
    }

    public function store(IllnessRequest $req){
        Illness::create($req->validated());

        CLog::create('Created', 'Rabbit Illness');
    }

    public function updateStatus(Request $req){
        Illness::where('id', $req['id'])->update([
            'status' => $req['status']
        ]);

        CLog::create('Updated', 'Rabbit Illness');
    }
}
