<?php

namespace App\Http\Controllers\Agent;

use DB;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Facility;
use App\Models\Property;
use App\Models\Amenities;
use App\Models\MultiImage;
use App\Models\Propertitype;
use Illuminate\Http\Request;
use Intervention\Image\Image;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Intervention\Image\ImageManager;
use Illuminate\Support\Facades\Session;
use Intervention\Image\Drivers\Gd\Driver;
use Haruncpi\LaravelIdGenerator\IdGenerator;
use App\Models\PackagePlan;



class AgentPropertyController extends Controller
{
    public function AgentAllProperty(){

        $id = Auth::user()->id;
        $property = Property::where('agent_id',$id)->latest()->get();

        return view('agent.property.all_property',compact('property'));

    } // End Method


    public function AgentAddProperty(){

        $propertytype = PropertiType::latest()->get();
        $amenities = Amenities::latest()->get();

        $id = Auth::user()->id;
        $property = User::where('role','agent')->where('id',$id)->first();
        $pcount = $property->credit;
        // dd($pcount);

        if ($pcount == 1 || $pcount == 7) {
           return redirect()->route('buy.package');
        }else{

            return view('agent.property.add_property',compact('propertytype','amenities'));
        }
        
       

    }// End Method

    // AgentStoreProperty



    public function AgentStoreProperty(Request $request)
    {
        $id = Auth::user()->id;
         $uid = User::findOrFail($id);
         $nid = $uid->credit;


        $manager = new ImageManager(new Driver());

        $amenites = implode(",", $request->amenities_id);

        $pcode = IdGenerator::generate([
            'table' => 'properties',
            'field' => 'property_code',
            'length' => 5,
            'prefix' => 'PC'
        ]);

        // Handle Thumbnail Upload
        $thumbImage = $request->file('property_thambnail');
        $thumbName = hexdec(uniqid()) . '.' . $thumbImage->getClientOriginalExtension();
        $thumbPath = 'upload/property/thambnail/' . $thumbName;

        $manager->read($thumbImage)->resize(370, 250)->save(public_path($thumbPath));

        // Insert Property
        $property_id = Property::insertGetId([
            'ptype_id' => $request->ptype_id,
            'amenities_id' => $amenites,
            'property_name' => $request->property_name,
            'property_slug' => strtolower(str_replace(' ', '-', $request->property_name)),
            'property_code' => $pcode,
            'property_status' => $request->property_status,
            'lowest_price' => $request->lowest_price,
            'max_price' => $request->max_price,
            'short_descp' => $request->short_descp,
            'long_descp' => $request->long_descp,
            'bedrooms' => $request->bedrooms,
            'bathrooms' => $request->bathrooms,
            'garage' => $request->garage,
            'garage_size' => $request->garage_size,
            'property_size' => $request->property_size,
            'property_video' => $request->property_video,
            'address' => $request->address,
            'city' => $request->city,
            'state' => $request->state,
            'postal_code' => $request->postal_code,
            'neighborhood' => $request->neighborhood,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'featured' => $request->featured,
            'hot' => $request->hot,
            'agent_id' => Auth::user()->id,
            'status' => 1,
            'property_thambnail' => $thumbPath,
            'created_at' => Carbon::now(),
        ]);

        // Upload Multiple Images
        if ($request->file('multi_img')) {
            foreach ($request->file('multi_img') as $img) {
                $multiName = hexdec(uniqid()) . '.' . $img->getClientOriginalExtension();
                $multiPath = 'upload/property/multi-image/' . $multiName;

                $manager->read($img)->resize(770, 520)->save(public_path($multiPath));

                MultiImage::insert([
                    'property_id' => $property_id,
                    'photo_name' => $multiPath,
                    'created_at' => Carbon::now(),
                ]);
            }
        }

        // Add Facilities
        if ($request->facility_name && $request->distance) {
            foreach ($request->facility_name as $index => $fname) {
                Facility::create([
                    'property_id' => $property_id,
                    'facility_name' => $fname,
                    'distance' => $request->distance[$index],
                ]);
            }
        }
        User::where('id',$id)->update([
             'credit' => DB::raw('1 + '.$nid),
         ]);
        $notification = [
            'message' => 'Property Inserted Successfully',
            'alert-type' => 'success'
        ];

        return redirect()->route('agent.all.property')->with($notification);
    }


    public function AgentEditProperty($id){


        $facilities = Facility::where('property_id',$id)->get();
        $property = Property::findOrFail($id);

        $type = $property->amenities_id;
        $property_ami = explode(',', $type);

        $multiImage = MultiImage::where('property_id',$id)->get();

        $propertytype = PropertiType::latest()->get();
        $amenities = Amenities::latest()->get();

        return view('agent.property.edit_property',compact('property','propertytype','amenities','property_ami','multiImage','facilities'));
    }// End Method




    public function AgentUpdateProperty(Request $request){

        $amen = $request->amenities_id;
        $amenites = implode(",", $amen);

        $property_id = $request->id;

        Property::findOrFail($property_id)->update([

            'ptype_id' => $request->ptype_id,
            'amenities_id' => $amenites,
            'property_name' => $request->property_name,
            'property_slug' => strtolower(str_replace(' ', '-', $request->property_name)),
            'property_status' => $request->property_status,

            'lowest_price' => $request->lowest_price,
            'max_price' => $request->max_price,
            'short_descp' => $request->short_descp,
            'long_descp' => $request->long_descp,
            'bedrooms' => $request->bedrooms,
            'bathrooms' => $request->bathrooms,
            'garage' => $request->garage,
            'garage_size' => $request->garage_size,

            'property_size' => $request->property_size,
            'property_video' => $request->property_video,
            'address' => $request->address,
            'city' => $request->city,
            'state' => $request->state,
            'postal_code' => $request->postal_code,

            'neighborhood' => $request->neighborhood,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'featured' => $request->featured,
            'hot' => $request->hot,
            'agent_id' => Auth::user()->id,
            'updated_at' => Carbon::now(),

        ]);

         $notification = array(
            'message' => 'Property Updated Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('agent.all.property')->with($notification);

    }// End Method



    public function AgentUpdatePropertyThambnail(Request $request)
    {
        $pro_id = $request->id;
        $oldImage = public_path($request->old_img); // ✅ Convert to full path

        $image = $request->file('property_thambnail');
        $name_gen = hexdec(uniqid()) . '.' . $image->getClientOriginalExtension();
        $save_path = 'upload/property/thambnail/' . $name_gen;

        // Save the new image without resizing or manipulating
        $image->move(public_path('upload/property/thambnail'), $name_gen);

        // Delete the old image if exists
        if (file_exists($oldImage)) {
            unlink($oldImage);
        }

        // Update DB
        Property::findOrFail($pro_id)->update([
            'property_thambnail' => $save_path,
            'updated_at' => Carbon::now(),
        ]);

        $notification = [
            'message' => 'Property Thumbnail Updated Successfully',
            'alert-type' => 'success'
        ];

        return redirect()->back()->with($notification);
    }

    public function AgentUpdatePropertyMultiimage(Request $request)
    {
        $imgs = $request->multi_img;

        foreach ($imgs as $id => $img) {
            $imgDel = MultiImage::findOrFail($id);

            // Full path of the old image
            $oldImagePath = public_path($imgDel->photo_name);

            // Delete the old image if exists
            if (file_exists($oldImagePath)) {
                unlink($oldImagePath);
            }

            // Generate new image name and save without resizing
            $make_name = hexdec(uniqid()) . '.' . $img->getClientOriginalExtension();
            $uploadPath = 'upload/property/multi-image/' . $make_name;

            // Move the new image to the specified folder without resizing
            $img->move(public_path('upload/property/multi-image'), $make_name);

            // Update DB
            MultiImage::where('id', $id)->update([
                'photo_name' => $uploadPath,
                'updated_at' => Carbon::now(),
            ]);
        }

        $notification = [
            'message' => 'Property Multiple Images Updated Successfully',
            'alert-type' => 'success'
        ];

        return redirect()->back()->with($notification);
    }



    public function AgentPropertyMultiimgDelete($id)
    {
        $oldImg = MultiImage::findOrFail($id);

        // Get full image path
        $imagePath = public_path($oldImg->photo_name);

        // Check if file exists before deleting
        if (File::exists($imagePath)) {
            File::delete($imagePath);
        }

        // Delete image record from database
        $oldImg->delete();

        $notification = [
            'message' => 'Property Multi Image Deleted Successfully',
            'alert-type' => 'success'
        ];

        return redirect()->back()->with($notification);
    }





    public function AgentStoreNewMultiimage(Request $request)
    {
        $request->validate([
            'imageid' => 'required|exists:properties,id',
            'multi_img' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $propertyId = $request->imageid;
        $image = $request->file('multi_img');

        $make_name = hexdec(uniqid()) . '.' . $image->getClientOriginalExtension();
        $save_path = 'upload/property/multi-image/' . $make_name;

        // Move uploaded file without resizing
        $image->move(public_path('upload/property/multi-image'), $make_name);

        MultiImage::create([
            'property_id' => $propertyId,
            'photo_name' => $save_path,
            'created_at' => now(),
        ]);

        $notification = [
            'message' => 'Property Multi Image Added Successfully',
            'alert-type' => 'success'
        ];

        return redirect()->back()->with($notification);
    }


    public function AgentUpdatePropertyFacilities(Request $request){

        $pid = $request->id;

        if ($request->facility_name == NULL) {
           return redirect()->back();
        }else{
            Facility::where('property_id',$pid)->delete();

          $facilities = Count($request->facility_name);

           for ($i=0; $i < $facilities; $i++) {
               $fcount = new Facility();
               $fcount->property_id = $pid;
               $fcount->facility_name = $request->facility_name[$i];
               $fcount->distance = $request->distance[$i];
               $fcount->save();
           } // end for
        }

         $notification = array(
            'message' => 'Property Facility Updated Successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);

    }// End Method







    public function AgentDeleteProperty($id)
    {
        $property = Property::findOrFail($id);

        // Delete main thumbnail if exists
        if (file_exists(public_path($property->property_thambnail))) {
            unlink(public_path($property->property_thambnail));
        }

        // Delete the property record
        $property->delete();

        // Delete associated multi-images
        $multiImages = MultiImage::where('property_id', $id)->get();

        foreach ($multiImages as $img) {
            if (file_exists(public_path($img->photo_name))) {
                unlink(public_path($img->photo_name));
            }
        }

        // Delete multi-image records in one go
        MultiImage::where('property_id', $id)->delete();

        // Delete facilities in one go
        Facility::where('property_id', $id)->delete();

        $notification = [
            'message' => 'Property Deleted Successfully',
            'alert-type' => 'success'
        ];

        return redirect()->back()->with($notification);
    }






    public function AgentDetailsProperty($id){

        $facilities = Facility::where('property_id',$id)->get();
         $property = Property::findOrFail($id);

         $type = $property->amenities_id;
         $property_ami = explode(',', $type);

         $multiImage = MultiImage::where('property_id',$id)->get();

         $propertytype = PropertiType::latest()->get();
         $amenities = Amenities::latest()->get();

         return view('agent.property.details_property',compact('property','propertytype','amenities','property_ami','multiImage','facilities'));

    }// End Method



    public function InactiveProperty(Request $request){

        $pid = $request->id;
        Property::findOrFail($pid)->update([

            'status' => 0,

        ]);

      $notification = array(
            'message' => 'Property Inactive Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('all.property')->with($notification);


    }// End Method


    public function ActiveProperty(Request $request){

        $pid = $request->id;
        Property::findOrFail($pid)->update([

            'status' => 1,

        ]);

      $notification = array(
            'message' => 'Property Active Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('all.property')->with($notification);


    }// End Method


    public function BuyPackage(){

        return view('agent.package.buy_package');
    }// End Method



    public function BuyBusinessPlan(){
 
        $id = Auth::user()->id;
        $data = User::find($id);
         return view('agent.package.business_plan',compact('data'));

    }// End Method  

    public function StoreBusinessPlan(Request $request){
        $id = Auth::user()->id;
        $uid = User::findOrFail($id);
        $nid = $uid->credit;

        PackagePlan::insert([

        'user_id' => $id,
        'package_name' => 'Business',
        'package_credits' => '3',
        'invoice' => 'ERS'.mt_rand(10000000,99999999),
        'package_amount' => '20',
        'created_at' => Carbon::now(), 
        ]);

        User::where('id',$id)->update([
            'credit' => DB::raw('3 + '.$nid),
        ]);



       $notification = array(
            'message' => 'You have purchase Basic Package Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('agent.all.property')->with($notification);  
    }// End Method 




    public function BuyProfessionalPlan(){
 
        $id = Auth::user()->id;
        $data = User::find($id);
        return view('agent.package.professional_plan',compact('data'));

    }// End Method  


     public function StoreProfessionalPlan(Request $request){

        $id = Auth::user()->id;
        $uid = User::findOrFail($id);
        $nid = $uid->credit;

      PackagePlan::insert([

        'user_id' => $id,
        'package_name' => 'Professional',
        'package_credits' => '10',
        'invoice' => 'ERS'.mt_rand(10000000,99999999),
        'package_amount' => '50',
        'created_at' => Carbon::now(), 
      ]);

        User::where('id',$id)->update([
            'credit' => DB::raw('10 + '.$nid),
        ]);



       $notification = array(
            'message' => 'You have purchase Professional Package Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('agent.all.property')->with($notification);  
    }// End Method









}
