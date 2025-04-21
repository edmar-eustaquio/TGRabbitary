<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Http\Requests\CategoryRequest;
use CLog;

class CategoryController
{
    
    public function index() {
        return inertia('Category/Index');
    }

    public function get(Request $req){
        return response(
            Category::paginate($req['page_size'] ?? 10)
        );
    }

    public function getAll(Request $req){
        if ($req['budget'])
            return response(
                Category::where('active', 1)->get()
            );

        return response(
            Category::withSum('budgets', 'amount')
                ->withSum('expenses', 'amount')
                ->where('active', 1)
                ->get()
                ->map(function ($val) {
                    $val->bal = ($val->budgets_sum_amount ?? 0) - ($val->expenses_sum_amount ?? 0);
                    return $val;
                })
        );
    }

    public function store(CategoryRequest $req){
        Category::create($req->validated());

        CLog::create('Created', 'Category');
    }

    public function update(CategoryRequest $req, $id){
        Category::where('id', $id)->update($req->validated());

        CLog::create('Updated', 'Category');
    }

    public function delete($id){
        Category::where('id', $id)->delete();

        CLog::create('Deleted', 'Category');
    }
}
