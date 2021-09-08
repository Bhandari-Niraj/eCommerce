<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Slider;
use App\Models\Product;
use App\Models\Category;
use App\Cart;
use Session;

class ClientController extends Controller
{
    public function home()
    {
        $sliders = Slider::All()->where('status', 1);
        $products = Product::All()->where('status', 1);

        return view('client.home')->with('sliders', $sliders)->with('products', $products);
    }

    public function shop()
    {
        $categories = Category::All();
        $products = Product::All()->where('status', 1);
        return view('client.shop')->with('categories', $categories)->with('products', $products);
    }

    public function addtocart($id)
    {
        $product = Product::find($id);
        $oldcart = Session::has('cart') ? Session::get('cart') : null; 
        $cart = new Cart($oldcart);
        $cart->add($product, $id);
        Session::put('cart', $cart);

        //dd(Session::get('cart'));
        return back();
    }

    public function update_qty(Request $request, $id)
    {
            $oldcart = Session::has('cart')? Session::get('cart') : Null; 
            $cart = new Cart($oldcart);
            $cart->updateQty($id, $request->quantity);
            Session::put('cart', $cart);

            return back();
        
    }

    public function cart()
    {
        if (!Session::has('cart')) {
            return redirect('/cart');
        }
        $oldcart = Session::has('cart') ? Session::get('cart') : null;
        $cart = new Cart($oldcart);
        return view('client.cart', ['products'=>$cart->items]);
    }

    public function checkout()
    {
        return view('client.checkout');
    }

    public function login()
    {
        return view('client.login');
    }

    public function signup()
    {
        return view('client.signup');
    }

    public function orders()
    {
        return view('admin.orders');
    }
}
