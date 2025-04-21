<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Setting;
use CLog;

class OrderController
{
    public function index(){
        return inertia('Order/Index');
    }

    public function detailsShow($id){
        return inertia('Order/DetailsShow',[
            'order' => Order::with([
                'user:id,name', 
                'details.product:id,name,price'
            ])->find($id)
        ]);
    }

    public function invoice($id){
        $d = Order::with([
            'user:id,name,email,phone_no', 
            'details.product:id,name,price'
        ])->find($id);
        abort_if(!$d, 404);

        $s = Setting::first();
        $i = 1;
        
        if($s){
            $i = ((int) $s->invoice_no) + 1;
            Setting::whereNotNull('invoice_no')->update([
                'invoice_no' => $i
            ]);
        } else{
            Setting::create(['invoice_no'=>1]);
        }

        return inertia('Order/Invoice',[
            'data' => $d,
            'invoice_no' => $i
        ]);
    }

    public function get(Request $req){
        return response(
            Order::with(['user:id,name', 'details.product:id,price'])
                ->where(function ($q) use($req){
                    $search = $req['search'];
                    // if ($search){
                    //     $q->where('category', 'LIKE', $search.'%')
                    //         ->orWhere('name', 'LIKE', '%'. $search.'%');
                    // }
                })
                ->orderByDesc('created_at')
                ->paginate($req['page_size'] ?? 10)
        );
    }

    public function getUser(Request $req,){
        return response(
            Order::with(['details.product:id,price'])
                ->where('user_id', auth()->user()->id)
                ->orderByDesc('created_at')
                ->paginate($req['page_size'] ?? 10)
        );
    }

    public function updateStatus(Request $req, $id){
        Order::where('id', $id)->update(['status' => $req['status']]);

        CLog::create('Update Status', 'Order');
    }

    public function markAsPaid($id){
        $order = Order::where('id', $id)->first();
        abort_if(!$order, 404);

        $order->update(['paid' => 1]);

       $details = \App\Models\OrderDetails::where('order_id', $id)->get();
        foreach($details as $detail){
            \App\Models\Rabbit::where('product_id', $detail->product_id)
                ->where('sold', 0)
                ->where('status', 'For Sale')
                ->take((int) $detail->qty)
                ->update([
                    'sold' => 1
                ]);
        }

        CLog::create('Mark as Paid', 'Order');
    }

    public function update(Request $req, $id){
        Order::where('id', $id)->update($req->validated());
    }

    public function delete($id){
        Order::where('id', $id)->delete();
    }
}
