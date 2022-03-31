<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tag_tweets extends Model
{
    use HasFactory;
    protected $table="tag_tweets";
    protected $guarded =[];

    public function tags(){
        return $this->belongsTo('App\Models\tags');
    }

    public function tweets(){
        return $this->belongsTo('App\Models\tweets');
    }
}
