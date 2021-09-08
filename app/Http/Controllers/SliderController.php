<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Slider;
use Illuminate\Support\Facades\Storage;

class SliderController extends Controller
{
    public function addslider()
    {
        return view('admin.addslider');
    }

    public function sliders()
    {
        $sliders = Slider::All();
        return view('admin.sliders')->with('sliders', $sliders);
    }

    public function saveslider( Request $request )
    {
        $request->validate([
           'description1'=>'required',
           'description2'=>'required',
           'slider_image'=>'image|nullable|max:1999|required'
        ]);

           // 1. Get file name with exetension
           $fileNameWithExtension = $request->File('slider_image')->getClientOriginalName();
           // 2. Just get filename
           $fileName = pathinfo($fileNameWithExtension, PATHINFO_FILENAME);
           // 3. Just get File extension only
           $extension = $request->file('slider_image')->getClientOriginalExtension();
           // 4. file Name to store
           $fileNameToStore = $fileName.'_'.time().'.'.$extension;

           // 5. Upload Image
           $path = $request->file('slider_image')->storeAs('public/slider_image', $fileNameToStore);
    

        $slider = new Slider();
        $slider->description1 = $request->input('description1');
        $slider->description2 = $request->input('description2');
        $slider->slider_image = $fileNameToStore;
        $slider->status = 1; 

        $slider->save();

        return back()->with('message', 'The slider has been sucessfully Saved.');
    }

    public function edit_slider($id)
    {
      $slider = Slider::find($id);
      return view('admin.edit_slider')->with('slider', $slider);
    }

    public function updateslider( Request $request)
    {
        $request->validate([
           'description1'=>'required',
           'description2'=>'required',
           'slider_image'=>'image|nullable|max:1999'
        ]);

        $slider = Slider::find($request->input('id'));
        $slider->description1 = $request->input('description1');
        $slider->description2 = $request->input('description2');

        if ($request->hasFile('slider_image')) {
           // 1. Get file name with exetension
           $fileNameWithExtension = $request->File('slider_image')->getClientOriginalName();
           // 2. Just get filename
           $fileName = pathinfo($fileNameWithExtension, PATHINFO_FILENAME);
           // 3. Just get File extension only
           $extension = $request->file('slider_image')->getClientOriginalExtension();
           // 4. file Name to store
           $fileNameToStore = $fileName.'_'.time().'.'.$extension;

           // 5. Upload Image
           $path = $request->file('slider_image')->storeAs('public/slider_image', $fileNameToStore);

           if ($product->product_image != 'noimage.jpg') {
               storage::delete('public/slider_image/'. $slider->slider_image);
           }
        }

        $slider->update();

        return redirect('/sliders')->with('message', 'The slider has been sucessfully Updated.');
    }

    public function deleteslider($id)
    {
        $slider = Slider::find($id);
        if ($slider->slider_image != 'noimage.jpg') 
       {
               storage::delete('puplic/slider_image/'. $slider->slider_image);
        }
        $slider->delete();

         return back()->with('message', 'The slider has been sucessfully Deleted.');      
    }

    public function activateslider($id)
    {
        $slider = Slider::find($id);
        $slider->status = 1;
        $slider->update();

        return back()->with('message', 'The slider has been sucessfully activated.');
    }

    public function deactivateslider($id)
    {
        $slider = Slider::find($id);
        $slider->status = 0;
        $slider->update();

        return back()->with('message', 'The slider has been sucessfully deactivated.');
    }
}
