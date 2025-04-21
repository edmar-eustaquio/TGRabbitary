<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Budget;
use App\Http\Requests\BudgetRequest;
use CLog;

class BudgetController
{
    
    public function index() {
        return inertia('Budget/Index');
    }

    public function expense() {
        return inertia('Budget/Expense');
    }

    public function report() {
        return inertia('Budget/Report',[
            'is_budget' => 1
        ]);
    }

    public function expenseReport() {
        return inertia('Budget/Report',[
            'is_budget' => 0
        ]);
    }

    public function get(Request $req){
        return response(
            Budget::with(['category:id,name'])
                ->whereHas('category', function($q){
                    $q->where('active', 1);
                })
                ->where('is_budget', $req['budget'] ?? 0)
                ->paginate($req['page_size'] ?? 10)
        );
    }

    public function getRange(Request $req){
        return response(
            Budget::with(['category:id,name'])
                ->whereHas('category', function($q){
                    $q->where('active', 1);
                })
                ->where(function($q) use($req){
                    $q->whereDate('created_at', '>=', $this->getDate($req['from']))
                        ->whereDate('created_at', '<=', $this->getDate($req['until']));
                })
                ->where('is_budget', $req['is_budget'] ?? 0)
                ->get()
        );
    }

    private function getDate($date){
        if (!$date) return substr(now(), 0, 10);

        return date('Y-m-d', strtotime(substr($date, 0, 10) . ' +1 day'));
    }

    public function store(BudgetRequest $req){
        Budget::create($req->validated());

        CLog::create('Created', $req['is_budget'] == 1 ? 'Budget' : 'Expense');
    }

    public function update(BudgetRequest $req, $id){
        Budget::where('id', $id)->update($req->validated());
        
        CLog::create('Updated', $req['is_budget'] == 1 ? 'Budget' : 'Expense');
    }

    public function delete($id){
        $d = Budget::where('id', $id)->first();
        abort_if(!$d, 404);

        $d->delete();
        
        CLog::create('Deleted', $d->is_budget == 1 ? 'Budget' : 'Expense');
    }

}
