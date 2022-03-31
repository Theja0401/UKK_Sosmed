<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\tweets;
use App\Models\tags;
use App\Models\tag_tweets;
use App\Models\comment;
class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {   
        $tweets = tweets::with('comment.user')->get();
        return view('home',['tweets' => $tweets]);
    }
    public function filter(Request $request)
    {
        $filter = $request->filter;
        $tweets = tags::where('name','like','%'.$filter."%")->pluck('id');
        
        return $tweets;
        $data=[];
        foreach ($tweets as $filter){
            $tweetsFiltered = tag_tweets::where('tag_id',$filter)->get();
            $data[]=$tweetsFiltered;
        }
        return $data;
    }
}

