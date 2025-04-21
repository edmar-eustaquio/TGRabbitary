<?php

namespace App\Http\Controllers;

use App\Models\OrderDetails;
use App\Models\User;
use App\Models\Rating;
use App\Models\Budget;
use App\Models\Category;

class DashboardController
{
    
    public function index(){
        $categories = Category::all();
        foreach($categories as $cat){
            $cat->bal = (Budget::where('is_budget', 1)->where('category_id', $cat->id)->sum('amount') ?? 0) - 
                (Budget::where('is_budget', 0)->where('category_id', $cat->id)->sum('amount') ?? 0);
        }

        $m = [];
        $t = 0;
        for ($i = 1; $i < 13; $i++){
            $c = 0;
            $h = OrderDetails::with(['product:id,price'])
                ->whereHas('order', function($q) use($i){
                    $q->whereMonth('created_at', $i);
                })->get();
            foreach($h as $v){
                $c += ((int) $v->qty) * ((int) $v->product->price);
            }
            $t += $c;
            array_push($m, $c);
        }

        return inertia('Dashboard', [
            'recent_orders' => OrderDetails::sum('qty'),
            'todays_orders' => OrderDetails::whereHas('order', function($q){
                    $q->whereDate('created_at', date('Y-m-d'))
                        ->whereDate('paid', 1);
                })->sum('qty'),
            'users' => User::where('admin', 0)->count('*'),
            'ratings' => Rating::count('*'),
            'all_budget' => (Budget::where('is_budget', 1)->sum('amount') ?? 0) - 
                (Budget::where('is_budget', 0)->sum('amount') ?? 0),
            'todays_budgets' => Budget::where('is_budget', 1)->whereDate('created_at', date('Y-m-d'))->sum('amount') ?? 0,
            'todays_expenses' => Budget::where('is_budget', 0)->whereDate('created_at', date('Y-m-d'))->sum('amount') ?? 0,
            'categories' => $categories,
            'monthly_income' => $m,
            'total_sales' => $t
        ]);
    }
    
}
