<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator; 


class ProfileController extends Controller
{
    public function update(Request $request){
    $validator = Validator::make($request->all(), [
        "email" => "required",
        "name" => "required",
        "profile" => "image|mimes:jpg,png,svg,jpeg'",
    ]);

    if($validator ->fails()){
        return $validator->messages();
    }

    if($request->hasFile('profile')){
        global $filename;
        $ldate = date('Y-m-d');
        $file = $request->file('profile');
        $title= Auth::user()->name;
        $extension = $file->getClientOriginalExtension();
        $filename = $title .'.' . $extension;
        $file->move('storage/profile/', $filename);
        }
        User::where('id',Auth::user()->id)->update(['profile'=>$filename,'name'=>$request->name,'email'=>$request->email]);
        return redirect('home');
    }
}
