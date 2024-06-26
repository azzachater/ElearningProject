<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Course;
use Illuminate\Support\Facades\Hash;
use DateTime;
class admincontroller extends Controller
{
    public function __construct(){
        // Constructor logic here if needed
    }

    public function admindashboard(){
        return view('admin.index');
    }// end method

 public function ReportView(){

        return view('admin.backend.report.report_view');

    }

    public function AdminPendingReview(){

        $review = Review::where('status',0)->orderBy('id','DESC')->get();
        return view('admin.backend.review.pending_review',compact('review'));

    }// End Method 

    public function UpdateReviewStatus(Request $request){

        $reviewId = $request->input('review_id');
        $isChecked = $request->input('is_checked',0);

        $review = Review::find($reviewId);
        if ($review) {
            $review->status = $isChecked;
            $review->save();
        }

        return response()->json(['message' => 'Review Status Updated Successfully']);

    }// End Method 

    public function AdminActiveReview(){

        $review = Review::where('status',1)->orderBy('id','DESC')->get();
        return view('admin.backend.review.active_review',compact('review'));

    }// End Method 
    public function SearchByDate(Request $request){

        $date = new DateTime($request->date);
        $formatDate = $date->format('d F Y');

        $payment = Payment::where('order_date',$formatDate)->latest()->get();
        return view('admin.backend.report.report_by_date',compact('payment','formatDate'));

    }// End Method 
    public function SearchByMonth(Request $request){

        $month = $request->month;
        $year = $request->year_name;

        $payment = Payment::where('order_month',$month)->where('order_year',$year)->latest()->get();
        return view('admin.backend.report.report_by_month',compact('payment','month','year'));

    }// End Method 


    public function SearchByYear(Request $request){

        $year = $request->year;

        $payment = Payment::where('order_year',$year)->latest()->get();
        return view('admin.backend.report.report_by_year',compact('payment', 'year'));

    }// End Method 
    public function adminlogout(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        $notification = array(
            'message' => 'Logout Successfully',
            'alert-type' => 'info'
        );

        return redirect('/admin/login')->with($notification);
    }// end method

    public function adminlogin(){
        return view('admin.admin_login');
    }// end method

    public function adminprofile(){
        $id=auth::user()->id;
        $profiledata = User::find($id);
        return view ('admin.admin_profile_view',compact('profiledata'));
    }// end method

    public function AdminProfileStore(Request $request){

        $id = Auth::user()->id;
        $data = User::find($id);
        $data->name = $request->name;
        $data->username = $request->username;
        $data->email = $request->email;
        $data->phone = $request->phone;
        $data->adress = $request->adress;

        if ($request->file('photo')) {
           $file = $request->file('photo');
           @unlink(public_path('upload/admin_images/'.$data->photo));
           $filename = date('YmdHi').$file->getClientOriginalName();
           $file->move(public_path('upload/admin_images'),$filename);
           $data['photo'] = $filename;
        }

        $data->save();

        $notification = array(
            'message' => 'Admin Profile Updated Successfully',
            'alert-type' => 'success'
        );
        return redirect()->back()->with($notification);

        return redirect()->back();

    }// End Method

    public function AdminChangePassword(){

        $id = Auth::user()->id;
        $profileData = User::find($id);
        return view('admin.admin_change_password',compact('profileData'));

    }// End Method


    public function AdminPasswordUpdate(Request $request){

        /// Validation
        $request->validate([
            'old_password' => 'required',
            'new_password' => 'required|confirmed'
        ]);

        if (!Hash::check($request->old_password, auth::user()->password)) {

            $notification = array(
                'message' => 'Old Password Does not Match!',
                'alert-type' => 'error'
            );
            return back()->with($notification);
        }

        /// Update The new Password
        User::whereId(auth::user()->id)->update([
            'password' => Hash::make($request->new_password)
        ]);

        $notification = array(
            'message' => 'Password Change Successfully',
            'alert-type' => 'success'
        );
        return back()->with($notification);

    }// End Method

    public function BecomeInstructor(){

        return view('frontend.instructor.reg_instructor');

    }// End Method

    public function InstructorRegister(Request $request){

        $request->validate([
            'name' => ['required','string','max:255'],
            'email' => ['required', 'string','unique:users'],
        ]);

        User::insert([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'phone' => $request->phone,
            'adress' => $request->adress,
            'password' =>  Hash::make($request->password),
            'role' => 'instructor',
            'status' => '0',
        ]);

        $notification = array(
            'message' => 'Instructor Registed Successfully',
            'alert-type' => 'success'
        );
        return redirect()->route('instructor.login')->with($notification);

    }// End Method

    public function AllInstructor(){

        $allinstructor = User::where('role','instructor')->latest()->get();
        return view('admin.backend.instructor.all_instructor',compact('allinstructor'));
    }// End Method

    public function UpdateUserStatus(Request $request){

        $userId = $request->input('user_id');
        $isChecked = $request->input('is_checked',0);

        $user = User::find($userId);
        if ($user) {
            $user->status = $isChecked;
            $user->save();
        }

        return response()->json(['message' => 'User Status Updated Successfully']);

    }// End Method

   


public function AdminAllCourse(){

    $course = Course::latest()->get();
    return view('admin.backend.courses.all_course',compact('course'));

}// End Method


public function UpdateCourseStatus(Request $request){

    $courseId = $request->input('course_id');
    $isChecked = $request->input('is_checked',0);

    $course = Course::find($courseId);
    if ($course) {
        $course->status = $isChecked;
        $course->save();
    }

    return response()->json(['message' => 'Course Status Updated Successfully']);

}// End Method

public function AdminCourseDetails($id){

    $course = Course::find($id);
    return view('admin.backend.courses.course_details',compact('course'));

}// End Method


}
