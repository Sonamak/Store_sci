<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Models\User;
use App\Models\UserField;

class Advertisement extends Model
{
    use HasFactory;
    Use SoftDeletes;

    protected $fillable = ['title', 'description', 'image', 'user_id', 'start_date', 'end_date'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'advertisement_user', 'advertisement_id', 'user_id')->withPivot('seen');
    }

    public function fields()
    {
        return $this->belongsToMany(UserField::class, 'advertisement_userField', 'advertisement_id', 'user_field_id')->withTimestamps();
    }

}
