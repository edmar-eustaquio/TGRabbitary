<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\CartRequest;
use App\Models\Cart;
use App\Models\Order;
use App\Models\Product;
use App\Models\OrderDetails;
use App\Models\Rabbit;

class CartController
{

    public function get() {
        return response(Cart::with(['product:id,name,images,stock,price'])
            ->where('user_id', auth()->user()->id)
            ->get());
    }

    public function buyNow(Request $req) {
        $req->validate([
            'product_id' => 'required|exists:products,id',
            'qty' => 'required',
            'address' => 'required',
            'payment_method' => 'required',
            'message_id' => 'nullable',
        ]);

        $product = Product::select(['id', 'stock', 'category'])
            ->where('active', 1)
            ->where('id', $req['product_id'])
            ->first();

        abort_if(!$product, 404);

        if ((int) $product->stock - (int) $req['qty'] < 0)
            throw new \Exception('Not enough stock');

        $order = Order::create([
            'no' => \Illuminate\Support\Str::uuid(),
            'user_id' => auth()->user()->id,
            'address' => $req['address'],
            'payment_method' => $req['payment_method'],
            'status' => 'Pending',
        ]);

        if ($req['message_id']){
            \App\Models\Message::where('id', $req['message_id'])->update(['order_id' => $order?->id]);
        }

        $product->update([
            'stock' => ((int) $product->stock - (int) $req['qty'])
        ]);

        // Rabbit::where('product_id', $product->id)
        //     ->where('sold', 0)
        //     ->where('status', 'For Sale')
        //     ->take((int) $req['qty'])
        //     ->update([
        //         'sold' => 1
        //     ]);
                
        OrderDetails::create([
            'product_id' => $product->id,
            'qty' => $req['qty'],
            'order_id' => $order->id
        ]);
    }

    public function checkout(Request $req) {
        $carts = Cart::with(['product:id,stock,name'])
            ->where('user_id', auth()->user()->id)
            ->get();
            
        foreach($carts as $cart){
            if ((int) $cart->product->stock - (int) $cart->qty < 0)
                throw new \Exception('Not enough stock for item ' . $cart->product->name);
        }

        Cart::where('user_id', auth()->user()->id)->delete();

        $order = Order::create([
            'no' => \Illuminate\Support\Str::uuid(),
            'user_id' => auth()->user()->id,
            'address' => $req['address'],
            'payment_method' => $req['payment_method'],
            'status' => 'Pending',
        ]);

        if ($req['message_id']){
            \App\Models\Message::where('id', $req['message_id'])->update(['order_id' => $order?->id]);
        }

        $d = [];
        foreach($carts as $cart){
            Product::where('id', $cart->product->id)->update([
                'stock' => ((int) $cart->product->stock - (int) $cart->qty)
            ]);

            // Rabbit::where('product_id', $cart->product->id)
            //     ->where('sold', 0)
            //     ->where('status', 'For Sale')
            //     ->take((int) $cart->qty)
            //     ->update([
            //         'sold' => 1
            //     ]);
                
            array_push($d, [
                'product_id' => $cart->product->id,
                'qty' => $cart->qty,
                'order_id' => $order->id
            ]);
        }

        OrderDetails::insert($d);
    }

    public function cancel($id){
        $order = Order::select(['id', 'paid'])
            ->where('id', $id)
            ->where('user_id', auth()->user()->id)
            ->where('status', 'Pending')
            ->first();

        $order->update(['status' => 'Cancelled']);

        $details = OrderDetails::with(['product:id,stock,category'])
            ->where('order_id', $order->id)
            ->get();

        foreach($details as $detail){
            Product::where('id', $detail->product_id)
                ->update([
                    'stock' => ((int) $detail->product->stock + (int) $detail->qty)
                ]);

            if ($order->paid == 1 && $detail->product->category == 'Rabbit'){
                Rabbit::where('product_id', $detail->product_id)
                    ->where('sold', 1)
                    ->where('status', 'For Sale')
                    ->take((int) $detail->qty)
                    ->update([
                        'sold' => 0
                    ]);
            }
        }
    }

    public function count(){
        return response(Cart::where('user_id', auth()->user()->id)->count('*'));
    }

    public function add(CartRequest $req) {
        $data = $req->validated();
        $data['user_id'] = auth()->user()->id;
        $last = Cart::with(['product:id,stock'])->where('user_id', auth()->user()->id)->where('product_id', $req['product_id'])->first();
        if (!$last){
            Cart::create($data);
            return;
        }

        $qty = (int) $last->qty + (int) $data['qty'];
        if ((int) $last->product->stock - $qty < 0)
            throw new \Exception('Not enough stock');

        $last->update([
            'qty' => $qty
        ]);
    }

    public function updateQty(Request $req, $id) {
        $last = Cart::with(['product:id,stock'])->where('user_id', auth()->user()->id)->where('id', $id)->first();
        if (!$last) return;
        
        if ((int) $last->product->stock - (int) $req['qty'] < 0)
            throw new \Exception('Not enough stock');

        $last->update([
            'qty' => $req['qty']
        ]);
        // Cart::where('id', $id)->where('user_id', 2)->update(['qty' => $req['qty']]);
    }

    public function delete($id) {
        Cart::where('id', $id)->where('user_id', auth()->user()->id)->delete();
    }

}
