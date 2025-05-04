<?php

namespace App\Http\Controllers\Backend;

use App\Models\User;
use App\Models\Facility;
use App\Models\Property;
use App\Models\Amenities;
use App\Models\MultiImage;
use App\Models\Propertitype;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Intervention\Image\Image;
use Illuminate\Support\Facades\File;
use App\Http\Controllers\Controller;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Haruncpi\LaravelIdGenerator\IdGenerator;
 use Carbon\Carbon;
 use App\Models\PackagePlan;
 use Barryvdh\DomPDF\Facade\Pdf;


class PropertyController extends Controller
{
    public function AllProperty(){

        $property = Property::latest()->get();
        return view('backend.property.all_property',compact('property'));

    } // End Method

    public function AddProperty(){
        $propertytype = Propertitype::latest()->get();
        $amenities = Amenities::latest()->get();
        $activeAgent = User::where('status','active')->where('role','agent')->latest()->get();
        return view('backend.property.add_property',compact('propertytype','amenities','activeAgent'));

    }// End Method


    // public function StoreProperty(Request $request) {
    //     $amen = $request->amenities_id;
    //     $amenites = implode(",", $amen);

    //     $pcode = IdGenerator::generate([
    //         'table' => 'properties',
    //         'field' => 'property_code',
    //         'length' => 5,
    //         'prefix' => 'PC'
    //     ]);

    //     $image = $request->file('property_thambnail');
    //     $name_gen = hexdec(uniqid()) . '.' . $image->getClientOriginalExtension();
    //     Image::make($image)->resize(370, 250)->save('upload/property/thambnail/' . $name_gen);
    //     $save_url = 'upload/property/thambnail/' . $name_gen;

    //     $property_id = Property::insertGetId([
    //         'ptype_id' => $request->ptype_id,
    //         'amenities_id' => $amenites,
    //         'property_name' => $request->property_name,
    //         'property_slug' => strtolower(str_replace(' ', '-', $request->property_name)),
    //         'property_code' => $pcode,
    //         'property_status' => $request->property_status,
    //         'lowest_price' => $request->lowest_price,
    //         'max_price' => $request->max_price,
    //         'short_descp' => $request->short_descp,
    //         'long_descp' => $request->long_descp,
    //         'bedrooms' => $request->bedrooms,
    //         'bathrooms' => $request->bathrooms,
    //         'garage' => $request->garage,
    //         'garage_size' => $request->garage_size,
    //         'property_size' => $request->property_size,
    //         'property_video' => $request->property_video,
    //         'address' => $request->address,
    //         'city' => $request->city,
    //         'state' => $request->state,
    //         'postal_code' => $request->postal_code,
    //         'neighborhood' => $request->neighborhood,
    //         'latitude' => $request->latitude,
    //         'longitude' => $request->longitude,
    //         'featured' => $request->featured,
    //         'hot' => $request->hot,
    //         'agent_id' => $request->agent_id,
    //         'status' => 1,
    //         'property_thambnail' => $save_url,
    //         'created_at' => Carbon::now(),
    //     ]);

    //     // Multiple Image Upload
    //     $images = $request->file('multi_img');
    //     foreach ($images as $img) {
    //         $make_name = hexdec(uniqid()) . '.' . $img->getClientOriginalExtension();
    //         Image::make($img)->resize(770, 520)->save('upload/property/multi-image/' . $make_name);
    //         $uploadPath = 'upload/property/multi-image/' . $make_name;

    //         MultiImage::insert([
    //             'property_id' => $property_id,
    //             'photo_name' => $uploadPath,
    //             'created_at' => Carbon::now(), // ✅ Correct field for timestamp
    //         ]);
    //     }

    //     /// Facilities Add From Here ////
    //     $facilities = count($request->facility_name);
    //     if ($facilities != NULL) {
    //         for ($i = 0; $i < $facilities; $i++) {
    //             $fcount = new Facility();
    //             $fcount->property_id = $property_id;
    //             $fcount->facility_name = $request->facility_name[$i];
    //             $fcount->distance = $request->distance[$i];
    //             $fcount->save();
    //         }
    //     }
    //     /// End Facilities  ////

    //     $notification = array(
    //         'message' => 'Property Inserted Successfully',
    //         'alert-type' => 'success'
    //     );

    //     return redirect()->route('all.property')->with($notification);
    // }
    public function StoreProperty(Request $request)
{
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
        'agent_id' => $request->agent_id,
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

    $notification = [
        'message' => 'Property Inserted Successfully',
        'alert-type' => 'success'
    ];

    return redirect()->route('all.property')->with($notification);
}


public function EditProperty($id){

    $property = Property::findOrFail($id);
    $facilities = Facility::where('property_id',$id)->get();

    $type = $property->amenities_id;
    $property_ami = explode(',', $type);


    $multiImage = MultiImage::where('property_id',$id)->get();

    $propertytype = Propertitype::latest()->get();
    $amenities = Amenities::latest()->get();
    $activeAgent = User::where('status','active')->where('role','agent')->latest()->get();

    return view('backend.property.edit_property',compact('property','propertytype','amenities','activeAgent','property_ami','multiImage','facilities'));

}// End Method



public function UpdateProperty(Request $request){

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
        'agent_id' => $request->agent_id,
        'updated_at' => Carbon::now(),

    ]);

     $notification = array(
        'message' => 'Property Updated Successfully',
        'alert-type' => 'success'
    );

    return redirect()->route('all.property')->with($notification);

}// End Method

public function UpdatePropertyThambnail(Request $request)
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

public function UpdatePropertyMultiimage(Request $request)
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


public function PropertyMultiImageDelete($id)
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




public function StoreNewMultiimage(Request $request)
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


public function UpdatePropertyFacilities(Request $request){

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


public function DeleteProperty($id)
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














public function DetailsProperty($id){

    $facilities = Facility::where('property_id',$id)->get();
    $property = Property::findOrFail($id);

    $type = $property->amenities_id;
    $property_ami = explode(',', $type);

    $multiImage = MultiImage::where('property_id',$id)->get();

    $propertytype = PropertiType::latest()->get();
    $amenities = Amenities::latest()->get();
    $activeAgent = User::where('status','active')->where('role','agent')->latest()->get();

    return view('backend.property.details_property',compact('property','propertytype','amenities','activeAgent','property_ami','multiImage','facilities'));

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

public function AdminPackageHistory(){

    $packagehistory = PackagePlan::latest()->get();
    return view('backend.package.package_history',compact('packagehistory'));


   }// End Method

   public function AdminPackageInvoice($id) {

    $packagehistory = PackagePlan::where('id',$id)->first();

    $pdf = Pdf::loadView('backend.package.package_history_invoice',compact('packagehistory'))->setPaper('a4')->setOption([
        'tempDir' => public_path(),
        'chroot' => public_path(),
    ]);
    return $pdf->download('invoice.pdf');


}// End Method









}
