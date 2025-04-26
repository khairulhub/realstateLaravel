<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;



class AdminController extends Controller
{
    public function AdminDashboard()
    {
        return view('admin.index');
    }

    public function AdminLogout(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();
        $notification = array(
            'message' => 'Admin Logout Successfully',
            'alert-type' => 'success'
        );

        return redirect('/admin/login')->with($notification);
    }

    public function AdminLogin()
    {
        return view('admin.admin_login');
    }

    public function AdminProfile()
    {

        $id = Auth::user()->id;
        $profileData = User::find($id);

        return view('admin.admin_profile_view', compact('profileData'));
    }

    public function AdminChangePassword()
    {

        $id = Auth::user()->id;
        $profileData = User::find($id);

        return view('admin.admin_change_password', compact('profileData'));
    }

    public function AdminProfileStore(Request $request)
    {
        $id = Auth::user()->id;
        $data = User::find($id);
        $data->name = $request->name;
        $data->username = $request->username;
        $data->email = $request->email;
        $data->phone = $request->phone;
        $data->address = $request->address;

        if ($request->file('photo')) {
            $file = $request->file('photo');
            @unlink(public_path('upload/admin_image/'.$data->photo));
            $filename = date('Ymdhi').$file->getClientOriginalName();
            $file->move(public_path('upload/admin_image'), $filename); // Fixing the path here
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

    public function AdminPasswordUpdate(Request $request)
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





    // Agent Registration
    public function AgentRegistration(Request $request){

        $request->validate([
            'name' => ['required','string', 'max:50'],
            'email'=>['required','string', 'unique:users']
         ]);

         User::insert([
             'name' => $request->name,
             'username' => $request->username,
             'email' => $request->email,
             'phone' => $request->phone,
             'address' => $request->address,
             'password' =>  Hash::make($request->password),
             'role' => 'agent',
            'status' => '0',
         ]);

         $notification = [
            'message' => 'Agent request send  Successfully ',
            'alert-type' => 'success',
        ];

        return redirect()->route('instructor.login')->with($notification);
    }



    public function AllAgent(){

        $allagent = User::where('role','agent')->get();
        return view('backend.agentuser.all_agent',compact('allagent'));

      }// End Method

      public function AddAgent(){

        return view('backend.agentuser.add_agent');

      }// End Method


      public function StoreAgent(Request $request){

        User::insert([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'password' => Hash::make($request->password),
            'role' => 'agent',
            'status' => 'active',
        ]);


           $notification = array(
                'message' => 'Agent Created Successfully',
                'alert-type' => 'success'
            );

            return redirect()->route('all.agent')->with($notification);


      }// End Method


      public function EditAgent($id){

        $allagent = User::findOrFail($id);
        return view('backend.agentuser.edit_agent',compact('allagent'));

      }// End Method


      public function UpdateAgent(Request $request){

        $user_id = $request->id;

        User::findOrFail($user_id)->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
        ]);


           $notification = array(
                'message' => 'Agent Updated Successfully',
                'alert-type' => 'success'
            );

            return redirect()->route('all.agent')->with($notification);

      }// End Method


      public function DeleteAgent($id){

        User::findOrFail($id)->delete();

         $notification = array(
                'message' => 'Agent Deleted Successfully',
                'alert-type' => 'success'
            );

            return redirect()->back()->with($notification);

      }// End Method


      public function changeStatus(Request $request){

        $user = User::find($request->user_id);
        $user->status = $request->status;
        $user->save();

        return response()->json(['success'=>'Status Change Successfully']);

      }// End Method






}
