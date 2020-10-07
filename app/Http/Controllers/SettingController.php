<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use File;
use Image;
use Auth;

class SettingController extends Controller
{
    // public function savePhoto($name, $profile_photo)
    // {
    //     $images = str_slug($name) . time() . '.' . $profile_photo->getClientOriginalExtension();

    //     $path = public_path('uploads/profile');

    //         //cek jika uploads/profile bukan direktori / folder
    //         if (!File::isDirectory($path)) {
    //              //maka folder tersebut dibuat
    //             File::makeDirectory($path, 0777, true, true);
    //         } 
        
    //         //simpan gambar yang diuplaod ke folrder uploads/profile
    //         Image::make($profile_photo)->save($path . '/' . $images);
    //         //mengembalikan nama file yang ditampung divariable $images
    //         return $images;

    // }

    public function profileSetting()
    {
        $user = Auth::user();
        return view('setting.profile', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string|max:75',
            'email' => 'required|email|exists:users,email',
            'role' => 'required',
            'password' => 'nullable|min:6',
            'profile_photo' => 'nullable|image|mimes:jpg,png,jpeg'
        ]);

        // try{

        //     $user = Auth::user();

        //     $profile_photo = $user->profile_photo;

        //     //cek jika ada file yang dikirim dari form
        //     if ($request->hasFile('profile_photo')) {
        //         //cek, jika photo tidak kosong maka file yang ada di folder uploads/profile akan dihapus
        //         !empty($profile_photo) ? File::delete(public_path('uploads/profile/' . $profile_photo)):null;
        //         //uploading file dengan menggunakan method saveFile() yg telah dibuat sebelumnya
        //         $profile_photo = $this->savePhoto($request->name, $request->file('profile_photo'));
        //     } 
        // } catch (\Exception $e) {
        //     return redirect()->back()
        //         ->with(['error' => $e->getMessage()]);
        // }

        $user = Auth::user();
        
        if($request->hasFile('profile_photo')){
            $photo_name = $user->id.'_profile_photo'.time().'.'.request()->profile_photo->getClientOriginalExtension();
            $request->profile_photo->storeAs('photos', $photo_name);
            $user->profile_photo = $photo_name;
        }

        $password = !empty($request->password) ? bcrypt($request->password):$user->password;

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'password' => $password
        ]);
        
        
        $user->save();

        return redirect()->back()->with(['success' => 'Profil berhasil diperbarui']);
    }
}
