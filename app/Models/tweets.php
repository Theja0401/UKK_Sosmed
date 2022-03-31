<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tweets extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $table="tweets";

    public function comment(){
        return $this->hasMany("App\Models\comment");
    }
    
    public function tag_tweets(){
        return $this->hasMany("App\Models\tag_tweets");
    }
}
