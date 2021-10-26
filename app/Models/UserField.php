<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

use App\Models\Advertisement;

class UserField extends Model {
    use HasFactory;
    Use SoftDeletes;
    
    protected $softDelete = true;

    protected $fillable = [
        "label",
        "field",
        "parent_id",
    ];

    protected $hidden = [
        'field',
        'parent_id',
    ];

    // Autorun
    public static function boot() {
        parent::boot();

        static::creating(function($model) {
            $model->created_by = Auth::id();
        });
    }

    // Relationships
    public function creator() {
        return $this->belongsTo('App\Models\User', 'created_by', 'id');
    }
    
    public function parent() {
        return $this->belongsTo('App\Models\UserField', 'parent_id', 'id');
    }

    public function children() {
        return $this->hasMany('App\Models\UserField', 'parent_id', 'id');
    }

    public function advertisements()
    {
        return $this->belongsToMany(Advertisement::class, 'advertisement_userField', 'user_field_id', 'advertisement_id')->withTimestamps();
    }
}