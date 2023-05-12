<?php

namespace App\Laravue\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $primaryKey = 'id';
    protected $fillable =['title','content'];
    //
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
