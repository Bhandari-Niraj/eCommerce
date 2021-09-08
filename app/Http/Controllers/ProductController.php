<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Facades\Storage;
class ProductController extends Controller
{
    public function addproduct()
    {
        $categories = Category::All()->pluck('category_name', 'category_name');
        return view('admin.addproduct')->with('categories', $categories);
    }

    public function products()
    {
        $products = Product::All();
        return view('admin.products')->with('products', $products);
    }
    public function saveproduct(Request $request)
    {
        $request->validate([
           'product_name'=>'required',
           'product_price'=>'required',
           'product_category'=>'required',
           'product_image'=>'image|nullable|max:1999'
        ]);
        if ($request->hasFile('product_image')) {
           // 1. Get file name with exetension
           $fileNameWithExtension = $request->File('product_image')->getClientOriginalName();
           // 2. Just get filename
           $fileName = pathinfo($fileNameWithExtension, PATHINFO_FILENAME);
           // 3. Just get File extension only
           $extension = $request->file('product_image')->getClientOriginalExtension();
           // 4. file Name to store
           $fileNameToStore = $fileName.'_'.time().'.'.$extension;

           // 5. Upload Image
           $path = $request->file('product_image')->storeAs('public/product_image', $fileNameToStore);
        }else{
            $fileNameToStore = 'noimage.jpg';
        }

        $product = new Product();
        $product->product_name = $request->input('product_name');
        $product->product_price = $request->input('product_price');
        $product->product_category = $request->input('product_category');
        $product->product_image = $fileNameToStore;
        $product->status = 1; 

        $product->save();

        return back()->with('message', 'The product has been sucessfully Saved.');
    }

    public function editproduct($id)
    {
        $categories = Category::All()->pluck('category_name', 'category_name');
        $product = Product::find($id);
        return view('admin.edit_product')->with('product', $product)->with('categories', $categories);
    }

    public function updateproduct(Request $request)
    {
         $request->validate([
           'product_name'=>'required',
           'product_price'=>'required',
           'product_category'=>'required',
           'product_image'=>'image|nullable|max:1999'
        ]);

        $product = Product::find($request->input('id'));
        $product->product_name = $request->input('product_name');
        $product->product_price = $request->input('product_price');
        $product->product_category = $request->input('product_category');

        if ($request->hasFile('product_image')) {
           // 1. Get file name with exetension
           $fileNameWithExtension = $request->File('product_image')->getClientOriginalName();
           // 2. Just get filename
           $fileName = pathinfo($fileNameWithExtension, PATHINFO_FILENAME);
           // 3. Just get File extension only
           $extension = $request->file('product_image')->getClientOriginalExtension();
           // 4. file Name to store
           $fileNameToStore = $fileName.'_'.time().'.'.$extension;

           // 5. Upload Image
           $path = $request->file('product_image')->storeAs('public/product_image', $fileNameToStore);
           $product->product_image = $fileNameToStore;

           if ($product->product_image != 'noimage.jpg') {
               storage::delete('puplic/product_images/'. $product->product_image);
           }
        }

        $product->update();

        return redirect('/products')->with('message', 'The product has been sucessfully Updated.');
    }

    public function deleteproduct( $id )
    {
       $product = Product::find($id);

       if ($product->product_image != 'noimage.jpg') 
       {
               storage::delete('puplic/product_images/'. $product->product_image);
        }

       $product->delete();

       return back()->with('message', 'The product has been sucessfully deleted');
    }

    public function activateproduct($id)
    {
        $product = Product::find($id);
        $product->status = 1;
        $product->update();

        return back()->with('message', 'The product has sucessfully activated.
            ');
    }
    public function deactivateproduct($id)
    {
        $product = Product::find($id);
        $product->status = 0;
        $product->update();

        return back()->with('message', 'The product has sucessfully deactivated.
            ');
    }

    public function view_product_by_category($category_name)
    {
        $categories = Category::All();
        $products = Product::All()->where('product_category', $category_name)->where('status', 1); 

        return view('client.shop')->with('categories', $categories)->with('products', $products);

    }
}
