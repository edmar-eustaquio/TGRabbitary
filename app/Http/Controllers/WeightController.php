<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Weight;
use CLog;

class WeightController
{
    
    public function get($rabbit_id){
        $data = Weight::where('rabbit_id', $rabbit_id)->orderBy('date')->get()->toArray();
        
        return response([
            'dates' => array_column($data, 'date'),
            'weights' => array_column($data, 'weight'),
        ]);
    }

    public function add(Request $req){
        $req->validate([
            'rabbit_id' => 'required|exists:rabbits,id',
            'weight' => 'required',
            'date' => 'nullable'
        ]);
        
        Weight::create([
            'rabbit_id' => $req['rabbit_id'],
            'weight' => $req['weight'],
            'date' => $req['date'] ?? now()
        ]);

        CLog::create('Created', 'Weight');
    }

}
