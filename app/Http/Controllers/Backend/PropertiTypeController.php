<?php

namespace App\Http\Controllers\Backend;


use App\Models\Amenities;
use App\Models\Propertitype;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class PropertiTypeController extends Controller
{
    public function AllType(){
        $types = Propertitype::latest()->get();
        return view('backend.type.all_type',compact('types'));
    }

    public function AddType(){
        return view('backend.type.add_type');
    }

    public function StoreType(Request $request){
        $request->validate([
            'type_name' => 'required',
            'type_icon' => 'required',
        ]);
        
        Propertitype::insert([
            'type_name' => $request->type_name,
            'type_icon' => $request->type_icon,
        ]);

        $notification= array(
            'message' => 'Type Added Successfully',
            'alert-type' =>'success'
        );
        return redirect()->route('all.type')->with($notification);


    }

    public function EditType($id){
 
        $types = Propertitype::findOrFail($id);
        return view('backend.type.edit_type',compact('types'));

    }

    public function UpdateType(Request $request){
 
        $pid = $request->id;
     
        Propertitype::findOrFail($pid)->update([ 

            'type_name' => $request->type_name,
            'type_icon' => $request->type_icon, 
        ]);

          $notification = array(
            'message' => 'Property Type Updated Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('all.type')->with($notification);

    }// End Method 


    public function DeleteType($id){
 
        PropertiType::findOrFail($id)->delete();

         $notification = array(
            'message' => 'Property Type Deleted Successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);

    }// End Method 





    public function AllAmenitie(){
 
        $amenities = Amenities::latest()->get();
        return view('backend.amenities.all_amenities',compact('amenities'));

    } // End Method 

    public function AddAmenitie(){
        return view('backend.amenities.add_amenities');

        
    }// End Method 
    public function StoreAmenitie(Request $request){ 
        Amenities::insert([ 

            'amenitis_name' => $request->amenitis_name, 
        ]);

          $notification = array(
            'message' => 'Amenities Create Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('all.amenitie')->with($notification);

    }// End Method 



    public function EditAmenitie($id){
 
        $amenities = Amenities::findOrFail($id);
        return view('backend.amenities.edit_amenities',compact('amenities'));

    }// End Method 


    public function UpdateAmenitie(Request $request){ 
 
        $ame_id = $request->id;

        Amenities::findOrFail($ame_id)->update([ 

            'amenitis_name' => $request->amenitis_name, 
        ]);

          $notification = array(
            'message' => 'Amenities Updated Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('all.amenitie')->with($notification);

    }// End Method 


    public function DeleteAmenitie($id){
 
        Amenities::findOrFail($id)->delete();

        $notification = array(
            'message' => 'Amenities Deleted Successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);

    }// End Method 







}
