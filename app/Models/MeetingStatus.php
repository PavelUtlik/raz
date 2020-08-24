<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class MeetingStatus extends Model
{


    public function meeting()
    {
        return $this->belongsTo(Meeting::class);
    }

}