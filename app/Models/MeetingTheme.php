<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class MeetingTheme extends Model
{
    protected $fillable = [
        'name',
        'icon',
        'user_id',
    ];

    public function meeting()
    {
        return $this->belongsTo(Meeting::class);
    }
}