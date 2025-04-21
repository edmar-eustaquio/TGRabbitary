<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Breeding;
use App\Http\Requests\RabbitBreedRequest;
use CLog;

class BreedingController
{
    public function index(){
        return inertia('Rabbit/Breeding');
    }

    public function get(Request $req){
        return response(
            Breeding::with(['buck', 'doe'])->paginate($req['page_size'] ?? 10)
        );
    }

    public function store(RabbitBreedRequest $req){
        Breeding::create($req->validated());

        CLog::create('Created', 'Rabbit Breeding');
    }

    public function update(RabbitBreedRequest $req, $id){
        Breeding::where('id', $id)->update($req->validated());

        CLog::create('Updated', 'Rabbit Breeding');
    }

    public function delete($id){
        Breeding::where('id', $id)->delete();

        CLog::create('Deleted', 'Rabbit Breeding');
    }
}
