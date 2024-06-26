<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\SubCategory;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class CategoryController extends Controller
{
    public function AllCategory(){
        $category = Category::latest()->get();
        return view('admin.backend.category.all_category',compact('category'));

    }// End Method

    public function AddCategory(){
        return view('admin.backend.category.add_category');
    }// End Method
    public function StoreCategory(Request $request){
        // Créer une nouvelle instance de ImageManager en spécifiant le driver GD
        $manager = new ImageManager(array('driver' => 'gd'));

        if ($request->file('image')){
            // Utilisez le gestionnaire d'images pour traiter et enregistrer l'image
            $name_gen = hexdec(uniqid()).'.'.$request->file('image')->getClientOriginalExtension();
            $img = $manager->make($request->file('image'))->resize(370, 246);
            $img->save(base_path('public/upload/'.$name_gen));
            $save_url = 'upload'.$name_gen;

            Category::insert([
                'category_name' => $request->category_name,
                'category_slug' => strtolower(str_replace(' ','-',$request->category_name)),
                'image' => $save_url,
            ]);
        }

        $notification = array(
            'message' => 'Category Inserted Successfully',
            'alert-type' => 'success'
        );
        return redirect()->route('all.category')->with($notification);
    }


    public function EditCategory($id){

        $category = Category::find($id);
        return view('admin.backend.category.edit_category',compact('category'));
    }// End Method

    public function UpdateCategory(Request $request){
        $cat_id = $request->id;

        if ($request->file('image')) {
            // If a new image is provided, process and update it
            $manager = new ImageManager(new Driver());
            $name_gen = hexdec(uniqid()).'.'.$request->file('image')->getClientOriginalExtension();
            $img = $manager->read($request->file('image'));
            $img = $img->resize(370,246);
            $img->toJpeg(80)->save(base_path('public/upload/category/'.$name_gen));
            $save_url = 'upload/category/'.$name_gen;

            Category::find($cat_id)->update([
                'category_name' => $request->category_name,
                'category_slug' => strtolower(str_replace(' ','-',$request->category_name)),
                'image' => $save_url,
            ]);

            $notification = array(
                'message' => 'Category Updated with image Successfully',
                'alert-type' => 'success'
            );
            return redirect()->route('all.category')->with($notification);

        } else {
            // If no new image is provided, update without changing the image
            Category::find($cat_id)->update([
                'category_name' => $request->category_name,
                'category_slug' => strtolower(str_replace(' ','-',$request->category_name)),
            ]);

            $notification = array(
                'message' => 'Category Updated without image Successfully',
                'alert-type' => 'success'
            );
            return redirect()->route('all.category')->with($notification);
        }
    }// End Method

    public function DeleteCategory($id){
        // Find the category
        $category = Category::find($id);

        // Check if the category exists
        if(!$category) {
            $notification = array(
                'message' => 'Category not found',
                'alert-type' => 'error'
            );
            return redirect()->back()->with($notification);
        }

        // Delete the associated image if it exists
        if($category->image && file_exists(public_path($category->image))) {
            unlink(public_path($category->image));
        }

        // Delete the category
        $category->delete();

        $notification = array(
            'message' => 'Category Deleted Successfully',
            'alert-type' => 'success'
        );
        return redirect()->back()->with($notification);
    }// End Method
  ////////// All SubCategory Methods //////////////

  public function AllSubCategory(){

    $subcategory = SubCategory::latest()->get();
    return view('admin.backend.subcategory.all_subcategory',compact('subcategory'));

}// End Method

public function AddSubCategory(){

    $category = Category::latest()->get();
    return view('admin.backend.subcategory.add_subcategory',compact('category'));

}// End Method


public function StoreSubCategory(Request $request){

    SubCategory::insert([
        'category_id' => $request->category_id,
        'subcategory_name' => $request->subcategory_name,
        'subcategory_slug' => strtolower(str_replace(' ','-',$request->subcategory_name)),

    ]);

    $notification = array(
        'message' => 'SubCategory Inserted Successfully',
        'alert-type' => 'success'
    );
    return redirect()->route('all.subcategory')->with($notification);

}// End Method

public function EditSubCategory($id){

    $category = Category::latest()->get();
    $subcategory = SubCategory::find($id);
    return view('admin.backend.subcategory.edit_subcategory',compact('category','subcategory'));

}// End Method


public function UpdateSubCategory(Request $request){

    $subcat_id = $request->id;

    SubCategory::find($subcat_id)->update([
        'category_id' => $request->category_id,
        'subcategory_name' => $request->subcategory_name,
        'subcategory_slug' => strtolower(str_replace(' ','-',$request->subcategory_name)),

    ]);

    $notification = array(
        'message' => 'SubCategory Updated Successfully',
        'alert-type' => 'success'
    );
    return redirect()->route('all.subcategory')->with($notification);

}// End Method


public function DeleteSubCategory($id){

    SubCategory::find($id)->delete();

    $notification = array(
        'message' => 'SubCategory Deleted Successfully',
        'alert-type' => 'success'
    );
    return redirect()->back()->with($notification);

}// End Method 






}
