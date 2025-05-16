<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TestimonialController extends Controller
{
    public function AllTestimonials(){

        $testimonial = Testimonial::latest()->get();
        return view('backend.testimonial.all_testimonial',compact('testimonial'));

    } // End Method 

    public function AddTestimonials(){
        return view('backend.testimonial.add_testimonial');
    }// End Method 


public function StoreTestimonials(Request $request)
{
    // Validate input
    $request->validate([
        'name' => 'required|string|max:255',
        'position' => 'required|string|max:255',
        'message' => 'required|string',
        'image' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
    ]);

    $save_url = null;

    // Handle image upload if present
    if ($request->hasFile('image')) {
        $image = $request->file('image');
        $name_gen = hexdec(uniqid()) . '.' . $image->getClientOriginalExtension();
        $image->move(public_path('upload/testimonial'), $name_gen);
        $save_url = 'upload/testimonial/' . $name_gen;
    }

    // Insert data into database
    Testimonial::create([
        'name' => $request->name,
        'position' => $request->position,
        'message' => $request->message,
        'image' => $save_url, // can be null
    ]);

    // Notification
    $notification = [
        'message' => 'Testimonial Inserted Successfully',
        'alert-type' => 'success',
    ];

    return redirect()->route('all.testimonials')->with($notification);
}


 public function EditTestimonials($id){

        $testimonial = Testimonial::findOrFail($id);
        return view('backend.testimonial.edit_testimonial',compact('testimonial'));

    }// End Method 


    public function UpdateTestimonials(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'position' => 'required|string|max:255',
        'message' => 'required|string',
        'image' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
    ]);

    $test_id = $request->id;
    $testimonial = Testimonial::findOrFail($test_id);

    if ($request->hasFile('image')) {
        $image = $request->file('image');
        $name_gen = hexdec(uniqid()) . '.' . $image->getClientOriginalExtension();
        $image->move(public_path('upload/testimonial'), $name_gen);
        $save_url = 'upload/testimonial/' . $name_gen;

        // Update with image
        $testimonial->update([
            'name' => $request->name,
            'position' => $request->position,
            'message' => $request->message,
            'image' => $save_url,
        ]);
    } else {
        // Update without changing the image
        $testimonial->update([
            'name' => $request->name,
            'position' => $request->position,
            'message' => $request->message,
        ]);
    }

    $notification = [
        'message' => 'Testimonial Updated Successfully',
        'alert-type' => 'success',
    ];

    return redirect()->route('all.testimonials')->with($notification);
}



    public function DeleteTestimonials($id){

        $test = Testimonial::findOrFail($id);
        $img = $test->image;
        unlink($img);

        Testimonial::findOrFail($id)->delete();

         $notification = array(
            'message' => 'Testimonial Deleted Successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification); 

    }// End Method





}
