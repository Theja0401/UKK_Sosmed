<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;   
use App\Models\tweets;
use Illuminate\Support\Str;
use App\Models\tags;
use App\Models\tag_tweets;
use Carbon\Carbon;
class TweetsController extends Controller
{
    public function store(Request $request){
        // return $request->all();
        global $nama_file;
        global $nama_gambar;
        $validator = Validator::make($request->all(), [
            "post" => "required",
            "gambar_post" => "image|mimes:jpg,png,svg,jpeg'",
        ]);

        if($validator ->fails()){
           return $validator->messages();
        }
        if($request->hasFile('gambar_post')){
            global $filename;
            $ldate = date('Y-m-d');
            $file = $request->file('gambar_post');
            $title= Str::random(10);
            $extension = $file->getClientOriginalExtension();
            $filename = uniqid().'-'.$title .'.' . $extension;
            $file->move('storage/post/', $filename);
            }
        if($request->hasFile('file_post')){
            global $filepost;
            $ldate = date('Y-m-d');
            $file = $request->file('file_post');
            $title= Str::random(10);
            $extension = $file->getClientOriginalExtension();
            $filepost = uniqid().'-'.$title .'.' . $extension;
            $file->move('storage/post/', $filepost);
            }
            $tweets = new tweets;
            $tweets->text = $request->post;
            if($request->gambar_post != null){
                $nama_gambar = $filename;
            }
            if($request->file_post != null){
                $nama_file = $filepost;
            }
            $tweets->gambar = $nama_gambar;
            $tweets->file = $nama_file;
            $tweets->save();
            $last_id=$tweets->id;

            $text = $request->post;
            $name = explode('#',$text);
            array_shift($name);
            // return $name;

            foreach($name as $tags){
                $data[] = [
                    'name'=>$tags,
                ];
                $tags = tags::updateOrCreate(['name'=>$tags]);  
                tag_tweets::insert(['tweet_id'=>$last_id,'tag_id'=>$tags->id,'created_at'=>Carbon::now(),'updated_at'=>Carbon::now()]);
            }
            return redirect('home');
    }
    public function delete(request $request){
        $id=$request->delete_id;
        $nama_gambar=$request->gambar_name;
        
        // return $request->all();
        tweets::find($id)->delete();
        comment::find($id)->delete();
        tag_tweets::where('tweet_id',$id)->delete();
        // unlink(storage_path('post/'.$nama_gambar));
        return redirect('home');
    }
    public function get($id){
        return tweets::find($id);
    }
    public function update(Request $request){
        // return $request->all();
        global $nama_file;
        global $nama_gambar;
        $value_gambar = $request->gambar_value;
        $validator = Validator::make($request->all(), [
            "post" => "required",
            "gambar_post" => "image|mimes:jpg,png,svg,jpeg'",
        ]);

        if($validator ->fails()){
           return $validator->messages();
        }

            if($request->hasFile('gambar_post_edit')){
                global $filename;
                $file = $request->file('gambar_post_edit');
                $title= Str::random(10);
                $extension = $file->getClientOriginalExtension();
                $filename = uniqid().'-'.$title .'.' . $extension;
                $file->move('storage/post/', $filename);
            }
            if($request->hasFile('file_post_edit')){
                global $filepost;
                $file = $request->file('file_post_edit');
                $title= Str::random(10);
                $extension = $file->getClientOriginalExtension();
                $filepost = uniqid().'-'.$title .'.' . $extension;
                $file->move('storage/post/', $filepost);
            }
    
            $tweets = tweets::where('id',$request->edit_id);
            $text = $request->post;
            if($request->gambar_post_edit != null){
                $nama_gambar = $filename;
            }else{
                $nama_gambar = $value_gambar;
            }
            if($request->file_post_edit != null){
                $nama_file = $filepost;
            }
            $file = $nama_file;
            tweets::where('id',$request->edit_id)->update(['text'=>$text,'gambar'=>$nama_gambar,'file'=>$file]);
            
            $text = $request->post;
            $name = explode('#',$text);
            array_shift($name);
            
            foreach($name as $tags){
                $data[] = [
                    'name'=>$tags,
                ];
                $tags = tags::updateOrCreate(['name'=>$tags]);  
                tag_tweets::where('tweet_id',$request->edit_id)->delete();
                tag_tweets::insert(['tweet_id'=>$request->edit_id,'tag_id'=>$tags->id,'created_at'=>Carbon::now(),'updated_at'=>Carbon::now()]);
            }
            return redirect('home');
    }
}
