<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\PropertiType;
use App\Models\State;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\Image;



class StateController extends Controller
{
    public function AllState(){

        $state = State::latest()->get();
        return view('backend.state.all_state',compact('state'));

    } // End Method 




    public function AddState(){
        return view('backend.state.add_state');
    } // End Method 



 

public function StoreState(Request $request)
{
    // Validate input
    $request->validate([
        'state_name' => 'required|string|max:255',
        'state_image' => 'required|image|mimes:jpg,jpeg,png,gif',
    ]);

    // Handle image upload (without Intervention)
    $image = $request->file('state_image');
    $name_gen = hexdec(uniqid()) . '.' . $image->getClientOriginalExtension();
    $image->move(public_path('upload/state'), $name_gen);
    $save_url = 'upload/state/' . $name_gen;

    // Insert into database
    State::insert([
        'state_name' => $request->state_name,
        'state_image' => $save_url,
    ]);

    // Notification
    $notification = [
        'message' => 'State Inserted Successfully',
        'alert-type' => 'success',
    ];

    return redirect()->route('all.state')->with($notification);
}




public function EditState($id){

        $state = State::findOrFail($id);
        return view('backend.state.edit_state',compact('state'));

    }// End Method 


    public function UpdateState(Request $request)
{
    $request->validate([
        'state_name' => 'required|string|max:255',
        'state_image' => 'nullable|image|mimes:jpg,jpeg,png,gif',
    ]);

    $state_id = $request->id;

    if ($request->hasFile('state_image')) {
        $image = $request->file('state_image');
        $name_gen = hexdec(uniqid()) . '.' . $image->getClientOriginalExtension();
        $image->move(public_path('upload/state'), $name_gen);
        $save_url = 'upload/state/' . $name_gen;

        State::findOrFail($state_id)->update([
            'state_name' => $request->state_name,
            'state_image' => $save_url,
        ]);
    } else {
        State::findOrFail($state_id)->update([
            'state_name' => $request->state_name,
            // Keep old image
        ]);
    }

    $notification = [
        'message' => 'State Updated Successfully',
        'alert-type' => 'success',
    ];

    return redirect()->route('all.state')->with($notification);
}


public function DeleteState($id){

        $state = State::findOrFail($id);
        $img = $state->state_image;
        unlink($img);

        State::findOrFail($id)->delete();

         $notification = array(
            'message' => 'State Deleted Successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification); 

    }// End Method








}
