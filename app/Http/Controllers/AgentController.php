<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
 use Illuminate\Auth\Events\Registered;
 use App\Providers\RouteServiceProvider;
class AgentController extends Controller
{
    public function AgentDashboard()
    {
        return view('agent.index');
    }


    public function AgentRegister(Request $request){


        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'role' => 'agent',
            'status' => 'inactive',
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('agent.dashboard'));

    }// End Method


    public function AgentLogout(Request $request){
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

         $notification = array(
            'message' => 'Agent Logout Successfully',
            'alert-type' => 'success'
        );

        return redirect('/agent/login')->with($notification);
    }// End Method





    public function AgentLogin()
    {
        return view('agent.agent_login');
    }





    public function AgentProfile()
    {

        $id = Auth::user()->id;
        $profileData = User::find($id);

        return view('agent.agent_profile_view', compact('profileData'));
    }

    public function AgentChangePassword()
    {

        $id = Auth::user()->id;
        $profileData = User::find($id);

        return view('agent.agent_change_password', compact('profileData'));
    }

    public function AgentProfileStore(Request $request)
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
            @unlink(public_path('upload/agent_image/'.$data->photo));
            $filename = date('Ymdhi').$file->getClientOriginalName();
            $file->move(public_path('upload/agent_image'), $filename); // Fixing the path here
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

    public function AgentPasswordUpdate(Request $request)
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
}
