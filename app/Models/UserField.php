<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

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
}
