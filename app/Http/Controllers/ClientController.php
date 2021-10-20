<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Slider;
use App\Models\Product;
use App\Models\Category;
use App\Models\Client;
use Illuminate\Support\Facades\Hash;
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

       public function remove_from_cart($id)
    {
            $oldcart = Session::has('cart')? Session::get('cart') : Null; 
            $cart = new Cart($oldcart);
            $cart->removeItem($id);

            if (count($cart->items) > 0) {
                Session::put('cart', $cart);
            }else{
                Session::forget('cart');
            }
            

            return redirect('/cart');

    }

    public function cart()
    {
        if (!Session::has('cart')) {
            return view('client.cart');
        }
        $oldcart = Session::has('cart') ? Session::get('cart') : null;
        $cart = new Cart($oldcart);
        return view('client.cart', ['products'=>$cart->items]);
    }



    public function checkout()
    {
        if (!Session::has('client')) {
            return view('client.login');
        }
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

    public function create_account(Request $request)
    {
         $this->validate($request,[
           'email'=> 'email|required|unique:clients',
           'password'=> 'required|min:4'
         ]);

         $client = new Client();
         $client->email = $request->input('email');
         $client->password = bcrypt($request->input('password'));

         $client->save();
         return back()->with('status', 'Your account has been sucessfully created !');
     }

     public function access_account(Request $request)
     {
         $this->validate($request,[
           'email'=> 'email|required',
           'password'=> 'required'
         ]);

         $client = Client::where('email', $request->input('email'))->first();

         if ($client) {
             if (Hash::check($request->input('password'), $client->password)) {
                Session::put('client', $client);
                 return redirect('/shop');
             }
             else{
                return back()->with('status', 'Wrong email or password');
             }
         }
         else{
            return back()->with('status', 'You donot have account this email.');
         }

     }

    public function orders()
    {
        return view('admin.orders');
    }
}
