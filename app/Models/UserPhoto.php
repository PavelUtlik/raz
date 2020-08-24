<?php
/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 20.02.2020
 * Time: 19:11
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class   UserPhoto extends Model
{



    protected $fillable = [
        'name', 'user_id', 'is_main'
    ];




    public function scopeWithUrl($query)
    {
        return $query->select('*')->addSelect(DB::raw("CONCAT('".config('image.user_photo.url')."',name) AS url"));
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }



}