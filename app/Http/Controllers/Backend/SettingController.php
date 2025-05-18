<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\SmtpSetting;
use App\Models\SiteSetting;
use Intervention\Image\Facades\Image;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;



class SettingController extends Controller
{
    public function SmtpSetting(){

        $setting = SmtpSetting::find(1);
        return view('backend.setting.smpt_update',compact('setting'));

    }// End Method


public function UpdateSmtpSetting(Request $request){

        $stmp_id = $request->id;

        SmtpSetting::findOrFail($stmp_id)->update([

                'mailer' => $request->mailer,
                'host' => $request->host,
                'post' => $request->post,
                'username' => $request->username,
                'password' => $request->password,
                'encryption' => $request->encryption,
                'from_address' => $request->from_address,
        ]);


           $notification = array(
            'message' => 'Smtp Setting Updated Successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);



    }// End Method



public function UpdateSmtpSetting(Request $request){

        $stmp_id = $request->id;

        SmtpSetting::findOrFail($stmp_id)->update([

                'mailer' => $request->mailer,
                'host' => $request->host,
                'post' => $request->post,
                'username' => $request->username,
                'password' => $request->password,
                'encryption' => $request->encryption,
                'from_address' => $request->from_address,
        ]);


           $notification = array(
            'message' => 'Smtp Setting Updated Successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);



    }// End Method


public function SiteSetting(){

         $sitesetting = SiteSetting::find(1);
        return view('backend.setting.site_update',compact('sitesetting'));

    }// End Method


use Illuminate\Http\Request;
use App\Models\SiteSetting;

public function UpdateSiteSetting(Request $request)
{
    $request->validate([
        'support_phone' => 'required|string|max:20',
        'company_address' => 'required|string|max:255',
        'email' => 'required|email',
        'facebook' => 'nullable|url',
        'twitter' => 'nullable|url',
        'copyright' => 'required|string|max:255',
        'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);

    $site = SiteSetting::findOrFail($request->id);

    $data = [
        'support_phone' => $request->support_phone,
        'company_address' => $request->company_address,
        'email' => $request->email,
        'facebook' => $request->facebook,
        'twitter' => $request->twitter,
        'copyright' => $request->copyright,
    ];

    if ($request->hasFile('logo')) {
        $file = $request->file('logo');
        $filename = hexdec(uniqid()) . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs('upload/logo', $filename, 'public'); // store in storage/app/public/upload/logo

        $data['logo'] = 'storage/' . $path; // use 'storage/' prefix for public access
        $message = 'Site Setting updated with logo successfully.';
    } else {
        $message = 'Site Setting updated successfully.';
    }

    $site->update($data);

    return redirect()->back()->with([
        'message' => $message,
        'alert-type' => 'success'
    ]);
}



}
