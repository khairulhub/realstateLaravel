<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Schedule;

class UserController extends Controller
{
    // User Dashboard
    public function index()
    {
        return view('frontend.index');
    }
// user profile dashboard
    public function UserProfile()
    {

        $id = Auth::user()->id;
        $userData = User::find($id);

        return view('frontend.dashboard.edit_profile',compact('userData'));
    }

    // User Profile Update
    public function UserProfileUpdate(Request $request){

        $id = Auth::user()->id;
        $data = User::find($id);
        $data->name = $request->name;
        $data->username = $request->username;
        $data->email = $request->email;
        $data->phone = $request->phone;
        $data->address = $request->address;

        if ($request->file('photo')) {
            $file = $request->file('photo');
            @unlink(public_path('upload/user_image/'.$data->photo));
            $filename = date('Ymdhi').$file->getClientOriginalName();
            $file->move(public_path('upload/user_image'), $filename); // Fixing the path here
            $data['photo'] = $filename;
        }
        $data->save();

        // After saving, retrieve the updated profile data again
        // $profileData = $data;

        $notification = [
            'message' => 'Profile Updated Successfully',
            'alert-type' => 'success',
        ];

        return redirect()->back()->with($notification);
    }

    // user logout
    public function UserLogout( Request $request ){
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();
        $notification = [
            'message' => 'Logout Successfully',
            'alert-type' => 'success',
        ];

        return redirect('/login')->with($notification);
    }


    // user change password
    public function UserChangePassword()
    {

        $id = Auth::user()->id;
        $profileData = User::find($id);

        return view('frontend.dashboard.change_password', compact('profileData'));
    }




    public function UserPasswordUpdate(Request $request)
    {
        //validation
        $request->validate([
            'old_password' => 'required',
            'new_password' => 'required|confirmed',
        ]);

        if (! Hash::check($request->old_password, Auth::user()->password)) {
            $notification = [
                'message' => 'Old password does not match',
                'alert-type' => 'error',
            ];

            return back()->with($notification);
            // return back()->with('status', 'password-updated');
        }
        User::whereId(Auth::user()->id)->update([
            'password' => Hash::make($request->new_password),
        ]);
        $notification = [
            'message' => 'Password Successfully Updated',
            'alert-type' => 'success',
        ];

        return back()->with($notification);

    }

public function UserScheduleRequest(){

        $id = Auth::user()->id;
        $userData = User::find($id);

        $srequest = Schedule::where('user_id',$id)->get();
        return view('frontend.message.schedule_request',compact('userData','srequest'));

    } // End Method 

}
