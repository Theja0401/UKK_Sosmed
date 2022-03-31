<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\comment;
use App\Models\tag_comments;
use App\Models\tags;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator; 
use Carbon\Carbon;

class CommentController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "komentar" => "required",
        ]);

        if($validator ->fails()){
           return $validator->messages();
        }
        $komentar = new comment;
        $komentar->user_id = Auth::user()->id;
        $komentar->tweets_id = $request->tweets_id;
        $komentar->komentar = $request->komentar;
        $komentar->save();
        $last_id = $komentar->id;
        
        $text = $request->komentar;
        $name = explode('#',$text);
        array_shift($name);
        
        foreach($name as $tags){
            $data[] = [
                'name'=>$tags,
            ];
            $tags = tags::updateOrCreate(['name'=>$tags]);  
            tag_comments::insert(['comment_id'=>$last_id,'tag_id'=>$tags->id,'created_at'=>Carbon::now(),'updated_at'=>Carbon::now()]);
        }
        return redirect('home');        
    }
    public function delete(Request $request){
        $delete_id=$request->komentardelete_id;
        comment::where('id',$delete_id)->delete();
        tag_comments::where('comment_id',$delete_id)->delete();  

        return redirect('home');
    }
    public function get($id){
        return comment::find($id);
    }
    public function update(Request $request){
        $validator = Validator::make($request->all(), [
            "komentar" => "required",
        ]);

        if($validator ->fails()){
           return $validator->messages();
        }
        $comennt = comment::where('id',$request->komentaredit_id)->update(['komentar'=>$request->komentar]);

        $text = $request->komentar;
        $name = explode('#',$text);
        array_shift($name);
        tag_comments::where('comment_id',$request->komentaredit_id)->delete();  

        foreach($name as $tags){
            $data[] = [
                'name'=>$tags,
            ];
            $tags = tags::updateOrCreate(['name'=>$tags]);
            tag_comments::insert(['comment_id'=>$request->komentaredit_id,'tag_id'=>$tags->id,'created_at'=>Carbon::now(),'updated_at'=>Carbon::now()]);
        }
        return redirect('home');  
    }
}
