<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class comment extends Model
{
    use HasFactory;
    protected $table="comments";
    protected $guarded=[];

    public function tweet(){
        return $this->belongsTo('App\Models\tweets');
    }
    public function user(){
        return $this->belongsTo('App\Models\User');
    }
}
