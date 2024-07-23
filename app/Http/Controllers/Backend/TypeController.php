<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Type;

use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class TypeController extends Controller
{
    public function AllType(){
        $type = type::latest()->get();
        return view('admin.backend.type.all_type',compact('type'));

    }// End Method
    

    public function AddType(){
        return view('admin.backend.type.add_type');
    }// End Method

     public function StoreType(Request $request){

        $manager = new ImageManager(array('driver' => 'gd'));

        if ($request->file('image')){
            // Utilisez le gestionnaire d'images pour traiter et enregistrer l'image
            $name_gen = hexdec(uniqid()).'.'.$request->file('image')->getClientOriginalExtension();
            $img = $manager->make($request->file('image'))->resize(370, 246);
            $img->save(base_path('public/upload/type/'.$name_gen));
            $save_url = 'upload/type/'.$name_gen;

            Type::insert([
                'type_name' => $request->type_name,
                'description' => $request->description,
                'type_image' => $save_url,      
        
        ]);
    }
        $notification = array(
            'message' => 'Type Inserted Successfully',
            'alert-type' => 'success'
        );
        return redirect()->route('all.type')->with($notification);  

    }// End Method 
    public function Edittype($id){

        $type = type::find($id);
        return view('admin.backend.type.edit_type',compact('type'));
    }// End Method

    public function Updatetype(Request $request){
        $type_id = $request->id;

        if ($request->file('image')) {
            // If a new image is provided, process and update it
            $manager = new ImageManager(new Driver());
            $name_gen = hexdec(uniqid()).'.'.$request->file('image')->getClientOriginalExtension();
            $img = $manager->read($request->file('image'));
            $img = $img->resize(370,246);
            $img->toJpeg(80)->save(base_path('public/upload/type/'.$name_gen));
            $save_url = 'upload/type/'.$name_gen;

            type::find($type_id)->update([
                'type_name' => $request->type_name,
               'description' => $request->description,
                'image' => $save_url,
            ]);

            $notification = array(
                'message' => 'type Updated with image Successfully',
                'alert-type' => 'success'
            );
            return redirect()->route('all.type')->with($notification);

        } else {
            // If no new image is provided, update without changing the image
            type::find($type_id)->update([
                'type_name' => $request->type_name,
                'description' => $request->description,
            ]);

            $notification = array(
                'message' => 'type Updated without image Successfully',
                'alert-type' => 'success'
            );
            return redirect()->route('all.type')->with($notification);
        }
    }// End Method
    public function Deletetype($id){
        // Find the category
        $type = type::find($id);

        // Check if the category exists
        if(!$type) {
            $notification = array(
                'message' => 'type not found',
                'alert-type' => 'error'
            );
            return redirect()->back()->with($notification);
        }

        // Delete the associated image if it exists
        if($type->image && file_exists(public_path($type->image))) {
            unlink(public_path($type->image));
        }

        // Delete the category
        $type->delete();

        $notification = array(
            'message' => 'type Deleted Successfully',
            'alert-type' => 'success'
        );
        return redirect()->back()->with($notification);
    }// End Method
}