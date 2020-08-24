<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InterestedFilter extends Model
{

    protected $fillable = [
        'user_id',
        'latitude',
        'longitude',
        'max_location_range',
        'min_age',
        'max_age',
        'gender_code',
    ];

    public function user()
    {
        return $this->hasOne( User::class);
    }
}