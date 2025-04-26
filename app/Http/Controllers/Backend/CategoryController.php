<?php

namespace App\Http\Controllers\Backend;

use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Http\Request;
// use Intervention\Image\Image;
use App\Http\Controllers\Controller;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class CategoryController extends Controller
{
    public function AllCategory(){
        $categories = Category::latest()->get();
        return view('admin.backend.category.all_category', compact('categories'));
    }


    // Add Category
    public function AddCategory(){
        return view('admin.backend.category.add_category');
    }

    // Store Category
    public function StoreCategory(Request $request){
        if($request->file('photo')){

            $manager = new ImageManager(new Driver());
            $name_gen = hexdec(uniqid()).'.'.$request->file('photo')->getClientOriginalExtension();
            $img = $manager->read($request->file('photo'));
            $img->resize(370, 246);
            // $img = save(base_path('public/upload/category/' . $category));

            $save_url = 'upload/category/' . $name_gen; // Define the save URL



            $img->save(public_path($save_url));

            Category::insert([
                'category_name' => $request->category_name,
                'category_slug' => strtolower(str_replace(' ', '-', $request->category_name)),
                'photo' => $save_url,
            ]);
        }
        $notification= array(
            'message' => 'Category Added Successfully',
            'alert-type' =>'success'
        );
        return redirect()->route('all.category')->with($notification);


    }



    // edit category
    public function EditCategory($id){
        $category = Category::find($id);
        return view('admin.backend.category.edit_category', compact('category'));

    }

    // update category
    public function UpdateCategory(Request $request){
        $cat_id = $request->id;
    
        // Find the category by ID
        $category = Category::find($cat_id);
    
        if($request->file('photo')){
            // If a new image is uploaded, delete the old image
            if (file_exists($category->photo)) {
                unlink($category->photo);
            }
    
            $manager = new ImageManager(new Driver());
            $name_gen = hexdec(uniqid()).'.'.$request->file('photo')->getClientOriginalExtension();
            $img = $manager->read($request->file('photo'));
            $img->resize(370, 246);
    
            $save_url = 'upload/category/' . $name_gen;
            $img->save(public_path($save_url));
    
            // Update the category with the new image URL
            $category->update([
                'category_name' => $request->category_name,
                'category_slug' => strtolower(str_replace(' ', '-', $request->category_name)),
                'photo' => $save_url,
            ]);
    
            $notification= array(
                'message' => 'Category Updated with image Successfully',
                'alert-type' =>'success'
            );
    
            return redirect()->route('all.category')->with($notification);
        } else {
            // If no new image is uploaded, update the category without changing the image
            $category->update([
                'category_name' => $request->category_name,
                'category_slug' => strtolower(str_replace(' ', '-', $request->category_name)),
            ]);
    
            $notification= array(
                'message' => 'Category updated without image Successfully',
                'alert-type' =>'success'
            );
    
            return redirect()->route('all.category')->with($notification);
        }
    }

    // delete category
    public function DeleteCategory($id){
        $item = Category::find($id);
        $img = $item->photo;
        unlink($img);

        Category::find($id)->delete();


        $notification= array(
           'message' => 'Category Deleted Successfully',
            'alert-type' =>'success'
        );
        return redirect()->back()->with($notification);
    }
    



    // /////////// All SubCategory  Details ///////////////////////////

    public function AllSubCategory(){
         $subcategories = SubCategory::latest()->get();
        return view('admin.backend.subcategory.all_subcategory', compact('subcategories'));
    }
    // Add SubCategory
    public function AddSubCategory(){
        $category = Category::latest()->get();
        return view('admin.backend.subcategory.add_subcategory', compact('category'));
    }

    // Store SubCategory
      public function StoreSubCategory(Request $request){
     
        SubCategory::insert([
            'category_id' => $request->category_id,
            'subcategory_name' => $request->subcategory_name,
            'subcategory_slug' => strtolower(str_replace(' ', '-', $request->subcategory_name)),
            
        ]);
        $notification= array(
            'message' => 'SubCategory Added Successfully',
            'alert-type' =>'success'
        );
        return redirect()->route('all.subcategory')->with($notification);
    }




    // EditSubCategory
    public function EditSubCategory($id){
        $subcategory = SubCategory::find($id);
        $category = Category::latest()->get();
        return view('admin.backend.subcategory.edit_subcategory', compact('subcategory', 'category'));
    }



    //  UpdateSubCategory
    public function UpdateSubCategory(Request $request){
     //this id come form the edit category page input type hidden field
        $subcat_id = $request->id;

        SubCategory::find($subcat_id)->update([
            'category_id' => $request->category_id,
            'subcategory_name' => $request->subcategory_name,
            'subcategory_slug' => strtolower(str_replace(' ', '-', $request->subcategory_name)),
            
        ]);
        $notification= array(
            'message' => 'SubCategory Updated  Successfully',
            'alert-type' =>'success'
        );
        return redirect()->route('all.subcategory')->with($notification);
    }


    // DeleteSubCategory
    public function DeleteSubCategory($id){
        SubCategory::find($id)->delete();


        $notification= array(
           'message' => 'SubCategory Deleted Successfully',
            'alert-type' =>'success'
        );
        return redirect()->back()->with($notification);
    }


}
