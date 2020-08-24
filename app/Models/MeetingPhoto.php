<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class MeetingPhoto extends Model
{

    protected $fillable = [
        'name',
    ];

    public function meeting()
    {
        return $this->belongsTo(Meeting::class);
    }

}